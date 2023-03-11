<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('/logout', 'AuthController@logout');
        $router->get('/me/{id}/', 'AuthController@me');
        $router->put('/edit/{id}/', 'AuthController@edit');
        $router->get('/posts', 'PostController@index');
        // thêm xóa sửa bài viết sẽ do role writer mà chưa làm
        $router->post('/posts', 'PostController@store');
        $router->put('/posts/{id}', 'PostController@update');
        $router->delete('/posts/{id}', 'PostController@destroy');


        $router->group(['middleware' =>'role:admin', 'prefix'=>'admin', 'name'=>'admin.'], function () use ($router) {
            //role
            $router->get('/roles', 'Admin\RoleController@index');
            $router->post('/roles', 'Admin\RoleController@store');
            $router->put('/roles/{id}', 'Admin\RoleController@update');
            $router->delete('/roles/{id}', 'Admin\RoleController@destroy');
            //permission
            $router->get('/permissions', 'Admin\PermissionController@index');
            $router->post('/permissions', 'Admin\PermissionController@store');
            $router->put('/permissions/{id}', 'Admin\PermissionController@update');
            $router->delete('/permissions/{id}', 'Admin\PermissionController@destroy');
            //give permission to relo
            $router->post('/roles/{id}/permissions', 'Admin\RoleController@givepermission');
        });
    });
});



