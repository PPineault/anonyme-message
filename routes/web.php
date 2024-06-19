<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

Route::get('/', function () {
    return view('send-message');
});
Route::get('/send-message', function () {
    return view('send-message');
})->name('send-message');


Route::get('/send-message', [MessageController::class, 'index'])->name('send-message');
Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
Route::get('/messages', [MessageController::class, 'getMessages'])->name('messages.get');
Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('messages.destroy');
Route::put('/messages/{id}', [MessageController::class, 'update'])->name('messages.update');
Route::get('/messages/{id}/edit', [MessageController::class, 'edit'])->name('messages.edit');
