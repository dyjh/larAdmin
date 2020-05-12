<?php


namespace App\Http\Controllers;


class TestController
{
    public function test()
    {
        dd(auth('web')->attempt(['mobile' => '18228068397', 'password' => '123456']));
    }
}
