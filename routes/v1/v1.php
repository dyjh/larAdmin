<?php

Route::prefix('auth')
    ->namespace('api\\v1\\auth')
    ->group(base_path('routes/v1/auth.php'));


Route::prefix('user')
    ->namespace('api\\v1\\user')
    ->group(base_path('routes/v1/user.php'));
