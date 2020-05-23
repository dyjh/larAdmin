<?php
namespace App\Common\Helper;
/**
 * Url生成类
 *
 * Author: 芸众商城 www.yunzshop.com
 * Date: 21/02/2017
 * Time: 18:02
 */
class Url
{
    public static function shopUrl($uri)
    {
        if(empty($uri) || self::isHttp($uri)){
            return $uri;
        }
        $domain = request()->getSchemeAndHttpHost();
        return $domain . '/' .$uri;
    }

    public static function isHttp($url)
    {
        return (strpos($url,'http://') === 0 || strpos($url,'https://') === 0);
    }

}
