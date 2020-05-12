<?php

Route::prefix('auth')
    ->namespace('api\\v1\\auth')
    ->group(base_path('routes/v1/auth.php'));
