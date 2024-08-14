<?php

use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

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
Route::get('check-out/{id}', [StripeController::class, 'checkOut'])->name('check-out');
Route::post('pay', [StripeController::class, 'pay'])->name('pay');
Route::get('update-plan', [StripeController::class, 'updatePlan'])->name('update-plan');
Route::post('update-plan', [StripeController::class, 'updatePlan'])->name('update-plan');
Route::post('check-coupon', [StripeController::class, 'checkcoupon'])->name('check-coupon');
Route::get('card-list', [StripeController::class, 'getCradList'])->name('card-list');
Route::get('my-plan', [StripeController::class, 'myPlan'])->name('myPlan');
Route::post('cancel-subscription', [StripeController::class, 'cancelSubscription'])->name('cancel-subscription');
Route::get('check-out/{id}', [StripeController::class, 'checkOut'])->name('check-out');
Route::post('check-coupon', [StripeController::class, 'checkcoupon'])->name('check-coupon');
Route::get('update-plan', [StripeController::class, 'updatePlan'])->name('update-plan');
Route::post('update-plan', [StripeController::class, 'updatePlan'])->name('update-plan');
Route::get('logout', [StripeController::class, 'logout'])->name('logout');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    // Route::get('/', function () {
    //     return view('dashboard');
    // })->name('dashboard');
    Route::get('/', [StripeController::class, 'plan'])->name('plan');
});
