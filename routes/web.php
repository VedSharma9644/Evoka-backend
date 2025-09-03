<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
Route::middleware(['web'])->group(function () {

Auth::routes();

Route::get('/', function () {
    return redirect()->to(env('FRONTEND_URL'));
});

Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
});
Route::get('auth/facebook', function () {
    return Socialite::driver('facebook')->redirect();
});
Route::get('paypal/cancel', function () {
   return redirect(env('FRONTEND_URL').'payment/canceled');
});
 
Route::get('paypal/success/{event_id}/{uid}', [App\Http\Controllers\EventController::class, 'paypal_eventpart_success'])->name('paypal.success');
Route::get('paypal-subscription/success/{plan}/{uid}', [App\Http\Controllers\SubscriptionController::class, 'paypal_success'])->name('paypal.paypal_success');
 

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/admin/users', [App\Http\Controllers\HomeController::class, 'users'])->name('admin.users');
Route::get('/admin/events', [App\Http\Controllers\HomeController::class, 'events'])->name('admin.events');
 Route::patch('/admin/events/{event}/status', [App\Http\Controllers\HomeController::class, 'updateStatus'])->name('admin.events.updateStatus');
Route::get('/admin/events_participation', [App\Http\Controllers\HomeController::class, 'events_participation'])->name('admin.events_participation');
Route::put('/admin/events_participation/{id}', [App\Http\Controllers\HomeController::class, 'events_participation_update'])->name('admin.events_participation.update');




});

