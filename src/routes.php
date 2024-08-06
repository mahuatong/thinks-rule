<?php

use think\facade\Route;


Route::group('think/auth', function () {
    Route::any('operation', '\\thinks\\rule\\AuthController@operation');
    Route::any('query', '\\thinks\\rule\\AuthController@query');
    Route::any('check', '\\thinks\\rule\\AuthController@check');
    Route::any('verify', '\\thinks\\rule\\AuthController@verify');
    Route::any('translate', '\\thinks\\rule\\AuthController@translate');
});
