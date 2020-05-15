<?php


namespace App\Common\Helper;


use App\Common\Constants\Constant;
use App\Common\Constants\ErrorCode;

class ConstantHelper
{
    public static function message($constant, $code)
    {
        if (!class_exists($constant)) {
            return null;
        }
        if (isset($constant::$_msg[$code])) {
            return $constant::$_msg[$code];
        } else {
            return null;
        }
    }

    public static function errMessage(int $code):string
    {

        if (isset(ErrorCode::$_msg[$code])) {
            return ErrorCode::$_msg[$code];
          //  return new \Exception($msg, $code);
        } else {
            //return new \Exception("未知错误", -1);
            return "未知错误";
        }
    }
}
