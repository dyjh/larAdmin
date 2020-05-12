<?php


namespace App\Http\Controllers\Api;


use App\Exceptions\api\SmsException;
use Illuminate\Support\Facades\Redis;

class TestController
{
    public function index()
    {
        $redis = Redis::connection();
        dd($redis->lpop('111'));
    }
}
