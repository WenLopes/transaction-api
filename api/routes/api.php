<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::name('transaction.')->prefix('transaction')->group(function () {
    Route::post('/', [ TransactionController::class, 'create' ])->name('create');
});