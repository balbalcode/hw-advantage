<?php

use App\Actions\Weather\GetWeatherData;
use App\Actions\Weather\ShowWeatherPage;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowWeatherPage::class)->name('weather.index');
Route::post('/', GetWeatherData::class)->name('weather.get');
