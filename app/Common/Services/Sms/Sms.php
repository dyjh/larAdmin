<?php

namespace App\Common\Services\Sms;

/**
 * 短信类接口
 *
 * Interface Sms
 * @package App\Common\Services\Sms
 */
Interface Sms
{
    /**
     * 短信接口授权参数输入
     *
     * @param array $Auth
     * @return string
     */
    public function setAuth(array $Auth):string;

    /**
     * 获取短信接口类型
     *
     * @return string
     */
    public function getType() : string;

    /**
     * 短信发送
     *
     * @return string
     */
    public function send():string;
}


