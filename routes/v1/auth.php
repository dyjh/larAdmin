<?php
/**
 * 用户认证
 */

Route::group([], function (\Illuminate\Routing\Router $router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
});

Route::group([
    'middleware' => 'api',
], function (\Illuminate\Routing\Router $router) {
    $router->post('refresh', 'AuthController@refresh');
    $router->post('logout', 'AuthController@logout');
    $router->post('me', 'AuthController@me');
});

