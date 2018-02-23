<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 14/02/2018
 * Time: 5:08 PM
 */

namespace Snp\FlyView\Compiler;


use Illuminate\View\Compilers\BladeCompiler;

/**
 * Class BladeCompiler
 * @package Snp\FlyView\Compiler
 */
class IlluminateBladeCompiler extends BladeCompiler
{

    /**
     * Compile the view at the given path.
     *
     * @param  object  $path
     * @return void
     */
    public function compile($path = null)
    {

        if ($path) {
            $this->setPath($path);
        }

        if (! is_null($this->cachePath)) {
            $contents = $this->compileString($this->files->get($this->getPath()));

            $this->files->put($this->getCompiledPath($this->getPath()), $this->minify($contents));
        }
    }

    /**
     * minify string content before save
     * @param $contents
     * @return string
     */
    private function minify ($contents)
    {

        $replace = [
            '/<!--[^\[](.*?)[^\]]-->/s' => '',
            "/<\?php/"                  => '<?php ',
            "/\n([\S])/"                => ' $1',
            "/\r/"                      => '',
            "/\n/"                      => '',
            "/\t/"                      => ' ',
            '/ +/'                      => ' ',
        ];

        return preg_replace(array_keys($replace), array_values($replace), $contents);

    }

}