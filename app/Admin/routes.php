<?php

use Illuminate\Routing\Router;

Admin::routes();

\Illuminate\Support\Facades\Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('/plugins-manager', 'Plugins\ManagerController');
    //$router->any('/plugins-manager/{plugins}', 'Plugins\ManagerController@update');
    $router->post('/plugins-manager/install', 'Plugins\ManagerController@install');
    $router->post('/plugins-manager/uninstall', 'Plugins\ManagerController@uninstall');
    $router->resource('/diySetting', 'Config\ConfigController');
    $router->get('/setting_form', 'Config\NormalConfigController@form');
    $router->post('/setting_form_save', 'Config\NormalConfigController@settingFormSave');
    $router->get('/base/getConstantOption', 'Base\ConstantController@getOption');
    $router->resource('/plugins', PluginsController::class);
});
