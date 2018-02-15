<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 14/02/2018
 * Time: 11:30 AM
 */

namespace Snp\FlyView;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\Engine;
use Illuminate\View\View as IlluminateView;
use Snp\FlyView\Factory\Factory;

/**
 * Class View
 * @package Snp\FlyView
 */
class View extends IlluminateView
{

    /**
     * The view factory instance.
     *
     * @var \Illuminate\View\Factory
     */
    protected $factory;

    /**
     * The engine implementation.
     *
     * @var \Illuminate\Contracts\View\Engine
     */
    protected $engine;

    /**
     * The name of the view.
     *
     * @var string
     */
    protected $view;

    /**
     * The array of view data.
     *
     * @var array
     */
    protected $data;

    /**
     * The path to the view file.
     *
     * @var string
     */
    protected $path;

    /**
     * View constructor.
     * @param Factory $factory
     * @param Engine $engine
     * @param array $view
     * @param array $data
     */
    function __construct(Factory $factory, Engine $engine, array $view, $data = [])
    {
        $this->view = implode(' ', $view);
        $this->engine = $engine;
        $this->factory = $factory;

        $this->data = $data instanceof Arrayable ? $data->toArray() : (array) $data;

    }

    /**
     * Get the name of the view.
     *
     * @return string
     */
    public function getName()
    {
        return sha1($this->view ?? '') ;
    }

    /**
     * Get the evaluated contents of the view.
     *
     * @return string
     */
    protected function getContents()
    {
        return $this->engine->get($this->view, $this->gatherData());
    }

}