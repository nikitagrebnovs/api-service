<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', '\App\Http\Controllers\HomeController@index')->name('home');

Route::controller(\App\Http\Controllers\ExchangeController::class)->group(function () {
    Route::get('/price', 'calculatePrice')->name('exchange.calculate');
    Route::get('/exchange/rates', 'exchangeRates')->name('exchange.rates');
});
