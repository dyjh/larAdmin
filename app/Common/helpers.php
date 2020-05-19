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
