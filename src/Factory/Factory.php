<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 14/02/2018
 * Time: 2:54 PM
 */

namespace Snp\FlyView\Factory;


use Illuminate\Contracts\View\Factory as IlluminateViewFactoryContract;
use Illuminate\View\Factory as IlluminateViewFactory;
use Snp\FlyView\View as FlyView;

/**
 * Class Factory
 * @package Snp\FlyView\Factory
 */
class Factory extends IlluminateViewFactory implements IlluminateViewFactoryContract
{


    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|array  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\Contracts\View\View
     */
    public function make($view, $data = [], $mergeData = [])
    {

        $data = array_merge($mergeData, $this->parseData($data));

        //Render String Template

        if (is_array($view)) {

            return tap($this->flyViewInstance($view, $data), function ($view) {
                $this->callCreator($view);
            });

        }

        $path = $this->finder->find(
            $view = $this->normalizeName($view)
        );

        // Next, we will create the view instance and call the view creator for the view
        // which can set any data, etc. Then we will return the view instance back to
        // the caller for rendering or performing other view manipulations on this.

        return tap($this->viewInstance($view, $path, $data), function ($view) {
            $this->callCreator($view);
        });

    }

    /**
     * @param $view
     * @param $data
     * @return FlyView
     */
    protected function flyViewInstance ($view, $data)
    {

        return new FlyView($this, $this->engines->resolve('flyView.blade'), $view, $data);
    }

}