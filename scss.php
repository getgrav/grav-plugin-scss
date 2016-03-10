<?php
namespace Grav\Plugin;

use Grav\Common\Grav;
use Grav\Common\Plugin;

class ScssPlugin extends Plugin
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
        ];
    }

    /**
     * Activate plugin if path matches to the configured one.
     */
    public function onPluginsInitialized()
    {
        require_once(__DIR__.'/vendor/autoload.php');
        require_once(__DIR__.'/classes/ScssCompiler.php');

        Grav::instance()['scss'] = new ScssCompiler();
    }
}
