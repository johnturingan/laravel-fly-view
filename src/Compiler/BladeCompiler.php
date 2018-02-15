<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 14/02/2018
 * Time: 5:08 PM
 */

namespace Snp\FlyView\Compiler;


use Illuminate\View\Compilers\BladeCompiler as IlluminateBladeCompiler;

/**
 * Class BladeCompiler
 * @package Snp\FlyView\Compiler
 */
class BladeCompiler extends IlluminateBladeCompiler
{

    /**
     * Compile the view at the given path.
     *
     * @param  object  $path
     * @return void
     */
    public function compile($path = null)
    {

        $stringTemplate = $path;

        if (! is_null($this->cachePath)) {
            $contents = $this->compileString($stringTemplate);

            $this->files->put($this->getCompiledPath($stringTemplate), $contents);
        }
    }

    /**
     * Determine if the view at the given path is expired.
     *
     * @param  string  $path
     * @return bool
     */
    public function isExpired($path)
    {
        $compiled = $this->getCompiledPath($path);

        // If the compiled file doesn't exist we will indicate that the view is expired
        // so that it can be re-compiled. Else, we will verify the last modification
        // of the views is less than the modification times of the compiled views.
        if (! $this->files->exists($compiled)) {
            return true;
        }

        return false;
    }

}