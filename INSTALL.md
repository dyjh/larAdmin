
## 安装

- git clone 到本地
- 配置 **.env** 中数据库连接信息,没有.env请复制.env.example命名为.env
- 执行 `php artisan key:generate`
- 执行 `php artisan migrate`
- 执行 `php artisan db:seed --class=DatabaseSeeder`
- 执行 `php artisan passport:install`
- 执行 `php artisan storage:link`
- storage下所有目录 和 bootstrap/cache 目录应该是可写的
- 键入 '域名/admin'(后台登录)
- 用户名：admin；密码：admin，若数据库无数据请执行`php artisan admin:install`

- 生成文档 php artisan l5-swagger:generate 
- api文档在api/documentation里面, 也可以看上面的 `在线api文档`
- api文档接口调试授权生成器请在`config/l5-swagger.php`配置

## passport 9.1
获取client ID 和 秘钥（用于swagger接口调试申请token）

    php artisan passport:client --password 

## 自定义curd命令 生成model和controller

    php artisan generate:auto-make --table=users

## reliese/laravel 0.0.15 Model批量生成
注意：本插件仅在local环境使用

全部生成（慎用）

    php artisan code:models
    
生成某个表

    php artisan code:models --table=users

## encore/laravel-admin

curd根据Model生成Controller

    php artisan admin:make UserController --model=App\\Models\\Eloquent\\User

添加路由配置 `app/Admin/routes.php`

    $router->resource('users', UserController::class);

## laravels

启动

    php bin/laravels start
    
## USEFUL LINK
- transformer [fractal](http://fractal.thephpleague.com/)
- l5-swagger [https://github.com/DarkaOnLine/L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger)
- 参考文章 [http://oomusou.io/laravel/laravel-architecture](http://oomusou.io/laravel/laravel-architecture/)
- debug rest api [postman](https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop?hl=en)
