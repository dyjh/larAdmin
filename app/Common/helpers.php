<?php

use \Illuminate\Support\Arr;

if (! function_exists('file_url')) {

    /**
     * 自动创建图片链接
     *
     * @param string $path
     * @return string
     */
    function file_url(string $path) {
        $disk = env('FILESYSTEM_DRIVER', 'local');
        return config("filesystems.disks.$disk.url") . "/$path";
    }
}

if (! function_exists('array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        return Arr::get($array, $key, $default);
    }
}

if (!function_exists('assets')) {

    function assets($relativeUri)
    {
        // add query string to fresh cache
        if (Str::startsWith($relativeUri, 'styles') || Str::startsWith($relativeUri, 'scripts')) {
            return Url::shopUrl("resources/assets/dist/$relativeUri") . "?v=" . config('app.version');
        } elseif (Str::startsWith($relativeUri, 'lang')) {
            return Url::shopUrl("resources/$relativeUri");
        } else {
            return Url::shopUrl("resources/assets/$relativeUri");
        }
    }
}
if (!function_exists('static_url')) {

    function static_url($relativeUri)
    {
        return Url::shopUrl('static/' . $relativeUri);
    }
}

if (!function_exists('plugin')) {

    function plugin($id)
    {
        return app('plugins')->getPlugin($id);
    }
}

if (!function_exists('plugin_assets')) {

    function plugin_assets($id, $relativeUri)
    {
        if ($plugin = plugin($id)) {
            return $plugin->assets($relativeUri);
        } else {
            throw new InvalidArgumentException("No such plugin.");
        }
    }
}
