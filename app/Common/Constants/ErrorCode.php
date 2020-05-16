<?php
/**
 *------------------------------------------------------
 * BaseProcess.php
 *------------------------------------------------------
 *
 * @author    Mike
 * @date      2016/5/26 11:17
 * @version   V1.0
 *
 */

namespace App\Common\Constants;

final class ErrorCode extends Constant {
    //错误常量定义
    const PARAMS_ERROR = 101;
    const SMS_VERIFY_FAILED = 102;
    const CONFIGURE_SMS_TEMPLATE = 103;
    const MODULE_IS_NOT_USE = 104;
    const MOBILE_OR_PASSWORD_INCORRECT = 105;
    const ACCOUNT_IS_FREEZE = 106;
    const NO_LOGIN = 107;

    //错误常量枚举
    public static $_msg = [
        self::PARAMS_ERROR => '参数错误',
        self::SMS_VERIFY_FAILED => '验证码错误',
        self::MODULE_IS_NOT_USE => '模块不存在',
        self::CONFIGURE_SMS_TEMPLATE => '请先配置模板',
        self::MOBILE_OR_PASSWORD_INCORRECT => '用户名或者密码错误',
        self::ACCOUNT_IS_FREEZE => '您的账户已被冻结',
        self::NO_LOGIN => '用户未登录',
    ];
}
