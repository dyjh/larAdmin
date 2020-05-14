<?php


namespace App\Http\Model\Constants\Config;


use App\Common\Constants\Constant;

class ConfigType extends Constant
{
    const TEXT        = 1;
    const TEXTAREA    = 2;
    const JSON        = 3;
    const SWITCH      = 4;
    const TIME_PICKER = 5;
    const FILE        = 6;
    const SELECT      = 7;
    const JSON_ARRAY  = 8;
    const IMAGE       = 9;
    const IMAGES      = 10;
    const CHECKBOX    = 11;

    public static $_msg = [
        self::TEXT        => '纯文本',
        self::TEXTAREA    => '富文本',
        self::JSON        => 'JSON',
        self::SWITCH      => '开关',
        self::TIME_PICKER => '时间选择',
        self::FILE        => '文件上传',
        self::SELECT      => '下拉框',
        self::JSON_ARRAY  => 'JSON数组',
        self::IMAGE       => '单图上传',
        self::IMAGES      => '多图上传',
        self::CHECKBOX    => '多选框',
    ];
}
