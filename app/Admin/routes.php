<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');

    $router->get('view/logic', 'LogicController@logic');
    $router->get('view/logic/download', 'LogicController@export_logic');
    $router->get('view/math', 'MathController@math');
    $router->get('view/math/download', 'MathController@export_math');

});
