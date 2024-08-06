<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::group([
    'prefix' => 'think/auth',
], function () {
    Route::any('operation', "\\think\\rule\\AuthController@operation");
    Route::any('query', "\\think\\rule\\AuthController@query");
    Route::any('check', "\\think\\rule\\AuthController@check");
    Route::any('verify', "\\think\\rule\\AuthController@verify");
    Route::any('translate', "\\think\\rule\\AuthController@translate");
});

