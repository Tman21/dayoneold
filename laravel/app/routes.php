<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

/**
 * Pages controller
 */
Route::get('/', array(
        'as' => 'home',
        'uses' => 'PageController@getIndex',
    ));
Route::get('/about', array(
        'as'        => 'about',
        'uses'      => 'PageController@getAbout',
    ));
Route::get('/contact', array(
        'as'        => 'contact',
        'uses'      => 'PageController@getContact',
    ));
Route::get('/reportbug', array(
        'as'        => 'reportbug',
        'uses'      => 'PageController@getReportbug',
    ));
Route::get('/how-it-works', array(
        'as'        => 'how-it-works',
        'uses'      => 'PageController@getHowItWorks',
    ));

Route::get('/registration/student', array(
        'as'    => 'register.student',
        'uses'  => 'RegistrationController@getRegisterStudent',
    ));
Route::get('/registration/tutor', array(
        'as'    => 'register.expert',
        'uses'  => 'RegistrationController@getRegisterExpert',
    ));

/**
 * Categories controller
 */
Route::controller('categories', 'CategoryController', array(
        'getIndex' => 'categories.index',
    ));

/**
 * News controller (used for viewing info about our news items)
 */
Route::controller('news', 'NewsController');

/**
 * Users controller (used for public viewing of group user info)
 */
Route::controller('users', 'UsersController', array(
        'getTopChart'   => 'users.topcharts',
    ));

/**
 * User controller (used for private handling of an individual user account and info)
 * Also used for viewing an individual's profile
 */
Route::get('/user/forgot', array(
        'as'        => 'user.forgot',
        'uses'      => 'UserController@getForgot',
    ));
Route::get('/user/login', array(
        'as'        => 'login',
        'uses'      => 'UserController@getLogin',
    ));
Route::get('/user/logout', array(
        'as'        => 'logout',
        'uses'      => 'UserController@getLogout',
    ));

Route::controller('user', 'UserController', array(
        'getMyAccount'    => 'user.my-account',
    ));

//Route::controller('user', 'UserController', array(
//    ));