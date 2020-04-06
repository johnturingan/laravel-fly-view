<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 14/02/2018
 * Time: 11:28 AM
 */

namespace Snp\FlyView\Providers;


use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\ViewServiceProvider as IlluminateViewServiceProvider;
use Snp\FlyView\Compiler\BladeCompiler;
use Snp\FlyView\Compiler\IlluminateBladeCompiler;
use Snp\FlyView\Factory\Factory;

/**
 * Class FlyViewServiceProvider
 * @package Snp\FlyView\Providers
 */
class ViewServiceProvider extends IlluminateViewServiceProvider
{


    public function boot ()
    {
        // setup publishing of config
        $this->publishes([
            __DIR__.'/../config/view.php' => config_path('view.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(
            __DIR__.'/../config/view.php', 'view'
        );

        $this->registerFlyViewBladeCompiler();

        parent::register();

    }

    /**
     * Register the view environment.
     *
     * @return void
     */
    public function registerFactory()
    {
        $this->app->singleton('view', function ($app) {
            // Next we need to grab the engine resolver instance that will be used by the
            // environment. The resolver will be used by an environment to get each of
            // the various engine implementations such as plain PHP or Blade engine.
            $resolver = $app['view.engine.resolver'];

            $finder = $app['view.finder'];

            $factory = new Factory($resolver, $finder, $app['events']);

            // We will also set the container instance on this view environment since the
            // view composers may be classes registered in the container, which allows
            // for great testable, flexible composers for the application developer.
            $factory->setContainer($app);

            $factory->share('app', $app);

            return $factory;
        });
    }

    /**
     * Register the engine resolver instance.
     *
     * @return void
     */
    public function registerEngineResolver()
    {
        $this->app->singleton('view.engine.resolver', function () {
            $resolver = new EngineResolver;

            // Next, we will register the various view engines with the resolver so that the
            // environment will resolve the engines needed for various views based on the
            // extension of view file. We call a method for each of the view's engines.
            foreach (['file', 'php', 'blade', 'flyView'] as $engine) {
                $this->{'register'.ucfirst($engine).'Engine'}($resolver);
            }

            return $resolver;
        });

    }

    /**
     * Register the Fly View Blade compiler implementation.
     *
     * @return void
     */
    public function registerFlyViewBladeCompiler ()
    {

        $this->app->singleton('flyView.blade.compiler', function ($app) {
            return new BladeCompiler($app['files'], $app['config']['view.compiled']);
        });
    }

    /**
     * Register the Blade engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerFlyViewEngine ($resolver)
    {

        $resolver->register('flyView.blade', function () {
            return new CompilerEngine($this->app['flyView.blade.compiler']);
        });
    }

    /**
     * Register the Blade compiler implementation.
     *
     * @return void
     */
    public function registerBladeCompiler()
    {
        $this->app->singleton('blade.compiler', function ($app) {
            return new IlluminateBladeCompiler($app['files'], $app['config']['view.compiled']);
        });
    }

    /**
     * Register the Blade engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerBladeEngine($resolver)
    {

        $resolver->register('blade', function () {
            return new CompilerEngine($this->app['blade.compiler']);
        });
    }

}