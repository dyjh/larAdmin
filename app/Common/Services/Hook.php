<?php

namespace App\Common\Services;

use Illuminate\Support\Facades\Event;
use Closure;
use app\common\events;
use Illuminate\Support\Str;

class Hook
{
    /**
     * Add a route. A router instance will be passed to the given callback.
     *
     * @param Closure $callback
     */
    public static function addRoute(Closure $callback)
    {
        Event::listen(Events\ConfigureRoutes::class, function($event) use ($callback)
        {
            dd(23333);
            return call_user_func($callback, $event->router);
        });
    }

    public static function registerPluginTransScripts($id)
    {
        Event::listen(Events\RenderingFooter::class, function($event) use ($id)
        {
            $path   = app('plugins')->getPlugin($id)->getPath().'/';
            $script = 'lang/'.config('app.locale').'/locale.js';

            if (file_exists($path.$script)) {
                $event->addContent('<script src="'.plugin_assets($id, $script).'"></script>');
            }
        }, 999);
    }

    public static function addStyleFileToPage($urls, $pages = ['*'], $priority = 1)
    {
        Event::listen(events\RenderingHeader::class, function($event) use ($urls, $pages)
        {
            foreach ($pages as $pattern) {
                if (!Str::is($pattern,request()->getRequestUri()))
                    continue;

                foreach ((array) $urls as $url) {
                    $event->addContent("<link rel=\"stylesheet\" href=\"$url\">");
                }

                return;
            }

        }, $priority);
    }

    public static function addScriptFileToPage($urls, $pages = ['*'], $priority = 1)
    {
        Event::listen(events\RenderingFooter::class, function($event) use ($urls, $pages)
        {
            foreach ($pages as $pattern) {
                if (!Str::is($pattern,request()->getRequestUri()))
                    continue;

                foreach ((array) $urls as $url) {
                    $event->addContent("<script src=\"$url\"></script>");
                }

                return;
            }

        }, $priority);
    }
}
