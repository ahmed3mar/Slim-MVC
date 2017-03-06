<?php

//Route::get('/name/{name}', function() {
//
//})->setName('ali');

Route::get('/', 'IndexController@index');
Route::get('/users', 'IndexController@users');

//$app->get('/', 'App\Controllers\IndexController:index');

//$app->get('/', function() {
//    return view('home');
//});