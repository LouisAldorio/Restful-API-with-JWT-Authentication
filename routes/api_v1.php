<?php
Route::get('welcome', 'API\v1\WelcomeController@index');


Route::post('login','API\v1\AuthController@login');
Route::post('register','API\v1\AuthController@register');
Route::post('auth/recovery','API\v1\AuthController@recover');



Route::group(['middleware' => 'auth.jwt'],function(){
    Route::post('logout','API\v1\AuthController@logout');

    Route::get('employees','API\v1\EmployeeController@index');
    
    Route::post('employees','API\v1\EmployeeController@store');

    Route::get('employees/{id}','API\v1\EmployeeController@show');

    Route::put('employees/{id}','API\v1\EmployeeController@update');

    Route::delete('employees/{id}','API\v1\EmployeeController@destroy');

    //user Controller
    Route::get('profile','API\v1\UserController@index');

    Route::put('profile/password','API\v1\UserController@updatePassword');

    Route::put('profile','API\v1\Usercontroller@update');
});