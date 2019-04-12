<?php

Auth::routes();
Route::get('user/activation/{token}', 'Auth\LoginController@activateUser')->name('user.activate');
Route::get('/{socialNetwork}/login', 'Auth\LoginController@redirectToProvider');
Route::get('/{socialNetwork}/login/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index');
Route::post('/contact', 'ContactController@send');

Route::group(['middleware' => ['auth']], function() {
  Route::post('/upload/xray', 'XrayUploadController@store');
  Route::post('/analyze/{xray}', 'XrayUploadController@analyze');
  Route::post('/upload/image', 'ImageUploadController@store');

  Route::get('/account/{section}', 'AccountController@index')->name('account');
  Route::post('/account/{section}', 'AccountController@update');

  Route::post('/cards', 'CardController@getCards');
  Route::post('/card/{token}', 'CardController@getCard');
  Route::post('/card/{token}/update', 'CardController@update');
  Route::post('/card/{token}/delete', 'CardController@delete');

  Route::get('/plans', 'PlanController@index')->name('plans');
  Route::get('/subscribe', 'PlanController@subscribe')->name('subscribe');
  Route::get('/braintree', 'BraintreeController@token')->name('braintree.token');
  Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
  Route::post('/history', 'DiagnosisController@history');
  Route::post('/history/{slug}/delete', 'DiagnosisController@delete');
});
