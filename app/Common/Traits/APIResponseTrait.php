<?php

namespace App\Common\Traits;

use App\Common\Constants\ErrorCode;
use App\Common\Helper\ConstantHelper;

trait APIResponseTrait
{
    public function api($data, $code = 0, $message = '')
    {
        $ret = $this->genApiData($data, $code, $message);
        $status = $code === 0 ? 200 : 400;
        return response()->json($ret, $status);
    }

    public function error($code, $message = '', $data = null)
    {
        return $this->api($data, $code, $message);
    }

    private function genApiData($data, $code = 0, $message = '')
    {
        if ($code !== 0 && ErrorCode::PARAMS_ERROR && empty($message)) {
            $message = ConstantHelper::message(new ErrorCode(), $code);
        }
        return [
            'status'      => $code == 0,
            'status_code' => $code,
            'message'     => $message,
            'data'        => $data
        ];
    }
}
