<?php

namespace App\Http\Middleware;

use App\Common\Constants\ErrorCode;
use App\Common\Helper\ConstantHelper;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $path = $request->getPathInfo();
        $module = explode("/", $path);
        if ($module[1] == "api" && ! $request->expectsJson()) {
            header('Content-Type:application/json; charset=utf-8');
            header('HTTP/1.1 ' . 500 . ' Internal Server Error');
            // 确保FastCGI模式下正常
            header('Status:' . 500 . ' Internal Server Error');
            $code = ErrorCode::NO_LOGIN;
            $ret = [
                'status'     => false,
                'status_code'=> $code,
                'message'   => "用户未登录",
            ];
            echo json_encode($ret);
            exit;
        }
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
