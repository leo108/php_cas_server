<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(
    [
        'middleware' => 'auth',
    ],
    function () {
        Route::get('/', ['as' => 'home', 'uses' => 'HomeController@indexAction']);
        Route::post('changePwd', ['as' => 'password.change.post', 'uses' => 'HomeController@changePwdAction']);
    }
);

Route::get('oauth/{name}', ['as' => 'oauth.login', 'uses' => 'Auth\OAuthController@login']);
Route::get('oauth/{name}/callback', ['as' => 'oauth.callback', 'uses' => 'Auth\OAuthController@callback']);

if (config('cas_server.allow_reset_pwd')) {
    Route::group(
        [
            'middleware' => 'guest',
        ],
        function () {
            Route::get(
                'password/email',
                ['as' => 'password.reset.request.get', 'uses' => 'PasswordController@getEmail']
            );
            Route::post(
                'password/email',
                ['as' => 'password.reset.request.post', 'uses' => 'PasswordController@sendResetLinkEmail']
            );
            Route::get(
                'password/reset/{token?}',
                ['as' => 'password.reset.get', 'uses' => 'PasswordController@showResetForm']
            );
            Route::post('password/reset', ['as' => 'password.reset.post', 'uses' => 'PasswordController@reset']);
        }
    );
}

if (config('cas_server.allow_register')) {
    Route::group(
        [
            'middleware' => 'guest',
            'namespace'  => 'Auth',
        ],
        function () {
            Route::get('register', ['as' => 'register.get', 'uses' => 'RegisterController@show']);
            Route::post('register', ['as' => 'register.post', 'uses' => 'RegisterController@postRegister']);
        }
    );
}

Route::group(
    [
        'namespace'  => 'Admin',
        'middleware' => 'admin',
        'prefix'     => 'admin',
    ],
    function () {
        Route::get('home', ['as' => 'admin_home', 'uses' => 'HomeController@indexAction']);

        Route::resource(
            'user',
            'UserController',
            [
                'only'  => ['index', 'store', 'update'],
                'names' => [
                    'index'  => 'admin.user.index',
                    'store'  => 'admin.user.store',
                    'update' => 'admin.user.update',
                ],
            ]
        );

        Route::resource(
            'service',
            'ServiceController',
            [
                'only'  => ['index', 'store', 'update'],
                'names' => [
                    'index'  => 'admin.service.index',
                    'store'  => 'admin.service.store',
                    'update' => 'admin.service.update',
                ],
            ]
        );
    }
);