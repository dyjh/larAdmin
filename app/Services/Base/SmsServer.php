<?php


namespace App\Services\Base;


use App\Common\Constants\ErrorCode;
use App\Common\Constants\SmsEvent;
use App\Common\Helper\ConstantHelper;
use App\Exceptions\api\SmsException;
use App\Http\Model\Eloquent\SystemConfig;
use App\Http\Model\Eloquent\UserInfo;
use App\Services\Sms\Sms;
use Illuminate\Support\Facades\Redis;
use Mockery\Exception;

class SmsServer
{

    const METHOD_SEND   = 1;
    const METHOD_VERIFY = 2;

    /**
     * 验证码
     *
     * @var string
     */
    private string $code;

    /**
     * @var \Redis|Object
     */
    private Object $redis;

    /**
     * 验证码接口
     *
     * @var Sms|mixed
     */
    private Sms $sms;

    /**
     * 验证码使用场景
     *
     * @var string
     */
    private string $event;

    /**
     * 手机号
     *
     * @var string
     */
    private string $mobile;

    /**
     * SmsServer constructor.
     * 初始化短信发送参数
     * @param string $event
     * @param string $mobile
     * @param int $method
     * @throws SmsException
     */
    public function __construct(string $event, string $mobile, int $method)
    {
        if ($method == self::METHOD_SEND) {
            $type = SystemConfig::get('sms_config', 'sms_type', 'AliSmsServer');
            $typeClass = '\App\Services\Sms\\' . $type;
            if (!class_exists($type)) {
                throw new SmsException(ConstantHelper::errMessage(ErrorCode::MODULE_IS_NOT_USE), ErrorCode::MODULE_IS_NOT_USE);
            }
            $template = SystemConfig::get('sms_config', 'AliSmsServer');
            if (empty($template) && !isset($template[$event])) {
                throw new SmsException(ConstantHelper::errMessage(ErrorCode::CONFIGURE_SMS_TEMPLATE), ErrorCode::CONFIGURE_SMS_TEMPLATE);
            }
            $this->code = rand(1000, 9999);
            $this->sms = new $typeClass($mobile, $template[$event]['templateId'], $template[$event]['sign'], $this->code);
        }
        $this->event = $event;
        $this->mobile = $mobile;
        $this->redis = Redis::connection();
    }

    /**
     * 发送验证码
     *
     * @return bool
     * @throws SmsException
     */
    public function send() : bool
    {
        $Auth = [];
        if ($this->sms->getType() == 'AliSmsService') {
            $Auth['key'] = SystemConfig::get('ali_config', 'appKey');
            $Auth['secret'] = SystemConfig::get('ali_config', 'appSecret');
        }
        $this->checkMobile();
        $this->sms->setAuth($Auth);
        $this->sms->send();
        $this->redis->set("{$this->mobile}-{$this->event}", $this->code);
        $this->redis->expire("{$this->mobile}-{$this->event}", 300);
        return true;
    }

    /**
     * 校验验证码
     *
     * @param string $code
     * @return bool
     * @throws \Exception
     */
    public function check(string $code) : bool
    {
        if ($this->redis->get("{$this->mobile}-{$this->event}") != $code) {
            throw new \Exception(ConstantHelper::errMessage(ErrorCode::SMS_VERIFY_FAILED), ErrorCode::SMS_VERIFY_FAILED);
        }
        return true;
    }

    /**
     * 手机号检验
     *
     * @return bool
     * @throws SmsException
     */
    private function checkMobile ()
    {
        $check = UserInfo::where('mobile', $this->mobile);
        if ($this->event == SmsEvent::REGISTER && $check) {
            throw new SmsException(ConstantHelper::errMessage(SmsException::MOBILE_IS_REGISTER), SmsException::MOBILE_IS_REGISTER);
        }

        if ($this->event == SmsEvent::CHANGE_PASSWORD && !$check) {
            throw new SmsException(ConstantHelper::errMessage(SmsException::MOBILE_NEED_REGISTER), SmsException::MOBILE_NEED_REGISTER);
        }
        $send_times = $this->redis->get("send_limit_$this->mobile") ?? 4;
        $time = $this->redis->get("last_send_$this->mobile");
        if ($time + 300 > time()) {
            if ($send_times == 0) {
                throw new SmsException(ConstantHelper::errMessage(SmsException::COED_SEND_FREQUENT), SmsException::COED_SEND_FREQUENT);
            }
            $this->redis->decr("send_limit_$this->mobile");
            return true;
        } else {
            $this->redis->set("send_limit_$this->mobile", 5);
            $this->redis->decr("send_limit_$this->mobile");
            return true;
        }
    }
}
