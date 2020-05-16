<?php

use Illuminate\Support\Facades\Route;
/**
 * 用户认证
 */


Route::group([
    'middleware' => 'auth:api',
], function (\Illuminate\Routing\Router $router) {
    $router->get('me', 'UserCenterController@me');
});

