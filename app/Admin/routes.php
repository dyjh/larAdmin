<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('/diySetting', 'Config\ConfigController');
    $router->get('/setting_form', 'Config\NormalConfigController@form');
    $router->post('/setting_form_save', 'Config\NormalConfigController@settingFormSave');
    $router->get('/base/getConstantOption', 'Base\ConstantController@getOption');
});
