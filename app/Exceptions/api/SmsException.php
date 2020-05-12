<?php


namespace App\Exceptions\api;


class SmsException extends \Exception
{
    const SMS_AUTH_FAILED = 101;
    const ALI_SMS_ERROR = 102;
    const MOBILE_IS_REGISTER = 103;
    const MOBILE_NEED_REGISTER = 104;
    const COED_SEND_FREQUENT = 105;


    //错误常量枚举
    public static $_msg = [
        self::SMS_AUTH_FAILED => '短信授权失败',
        self::ALI_SMS_ERROR => '阿里云短信发送异常',
        self::MOBILE_IS_REGISTER => '该手机号已注册',
        self::MOBILE_NEED_REGISTER => '该手机号未注册',
        self::COED_SEND_FREQUENT => '短信发送频繁，请稍后再试',
    ];
}
