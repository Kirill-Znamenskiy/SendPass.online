<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/** @var \Illuminate\Routing\Router $router */
$router = \Illuminate\Support\Facades\Route::getFacadeRoot();


$router->get('/', 'MainController@create');

$router->get('/create/', '\Illuminate\Routing\RedirectController')->defaults('destination', '/')->defaults('status', 301);
$router->post('/create/', 'MainController@create')->name('create');
$router->any('/created/{secuuid}/', 'MainController@created')->name('created');
$router->any('/show/{secuuid}/', 'MainController@show')->name('show');
$router->any('/faq/', 'MainController@faq')->name('faq');

//$router->get('/tst/', 'TstController@tst');


//Auth::routes();
//{
//    $options = [];
//
//    // Authentication Routes...
//    $router->get('login', 'Auth\LoginController@showLoginForm')->name('login');
//    $router->post('login', 'Auth\LoginController@login');
//    $router->post('logout', 'Auth\LoginController@logout')->name('logout');
//
//    // Registration Routes...
//    if ($options['register'] ?? true) {
//        $router->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//        $router->post('register', 'Auth\RegisterController@register');
//    }
//
//    // Password Reset Routes...
//    if ($options['reset'] ?? true) {
//        $router->resetPassword();
//    }
//
//    // Password Confirmation Routes...
//    //if ($options['confirm'] ?? class_exists($router->prependGroupNamespace('Auth\ConfirmPasswordController'))) {
//    //    $router->confirmPassword();
//    //}
//
//    // Email Verification Routes...
//    //if ($options['verify'] ?? false) {
//    //    $router->emailVerification();
//    //}
//}



//$router->get('/lk', 'LkController@lk')->name('lk');
