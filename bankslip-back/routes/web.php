<?php

use App\Http\Controllers\BankslipController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/upload-csv', [BankslipController::class, 'uploadCsv']);

