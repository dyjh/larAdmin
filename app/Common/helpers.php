<?php

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
