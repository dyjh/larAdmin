<?php

namespace App\Services\Sms;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use App\Common\Constants\ErrorCode;
use App\Exceptions\api\SmsException;

/**
 * 短信接口
 *
 * Class AliSmsServer
 * @package App\Services\Sms
 */
class AliSmsServer implements Sms
{
    private array $params;

    public function __construct(string $mobile, string $templateId, string $sign, int $code)
    {
        $params = [
            'RegionId' => "cn-hangzhou",
            'PhoneNumbers' => "{$mobile}",
            'SignName' => "{$sign}",
            'TemplateCode' => "{$templateId}",
        ];
        if ($code != 0) {
            $params['TemplateParam'] = "{'code':'$code'}";
        }
        $this->params = $params;
    }

    /**
     * 阿里云发送短信
     *
     * @return string
     * @inheritDoc
     * @throws SmsException
     */
    public function send() : string
    {
        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => $this->params
                ])
                ->request()
                ->toArray();
            return $result['Message'];
        } catch (ClientException $e) {
            throw new SmsException($e->getErrorMessage(), ErrorCode::ALI_SMS_ERROR);
        } catch (ServerException $e) {
            throw new SmsException($e->getErrorMessage(), ErrorCode::ALI_SMS_ERROR);
        }
    }

    /**
     * 阿里云设置短信权限
     *
     * @param array $Auth
     * @return string
     * @inheritDoc
     * @throws SmsException
     */
    public function setAuth(array $Auth) : string
    {
        try {
            AlibabaCloud::accessKeyClient($Auth['key'], $Auth['secret'])
                ->regionId('cn-hangzhou')
                ->asDefaultClient();
        } catch (ClientException $e) {
            throw new SmsException($e->getErrorMessage(), ErrorCode::ALI_SMS_ERROR);
        }
        return "SUCCESS";
    }

    /**
     * @return string
     * @inheritDoc
     */
    public function getType() : string
    {
        return 'AliSmsService';
    }
}
