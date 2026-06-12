<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;

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

Route::get('/',[ProductController::class,'index'])
->name('index');
Route::get('/search',[ProductController::class,'search'])
->name('search');
Route::get('/item/{item_id}',[ItemController::class,'show'])
->name('items.show');

Route::middleware('guest')->group(function(){
    Route::get('/register',[AuthController::class,'createStep1'])
    ->name('register.step1');
    Route::post('/register',[AuthController::class,'postStep1'])
    ->name('register.step1.post');
    Route::get('/login',[AuthController::class,'showLogin'])->name('login');
    Route::post('/login',[AuthController::class,'login'])->name('login.post');
});
Route::middleware('auth')->group(function(){
    Route::get('/email/verify', function(){
        return view('auth.verify-email');
        })->name('verification.notice');
    Route::post('/email/verification-notification',function(Request $request){
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status','verification-link-sent');
    })->middleware('throttle:6,1')
    ->name('verification.send');
    Route::get('/email/verify/{id}/{hash}', function(EmailVerificationRequest $request){
        $request->fulfill();
        return redirect()->route('register.step2');
    })->middleware('auth','signed')->name('verification.verify');
    Route::post('/logout',[AuthController::class,'logout'])
        ->name('logout');
});

Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/register/step2',[AuthController::class, 'createStep2'])
    ->name('register.step2');
    Route::post('/register/store',[AuthController::class,'store'])
    ->name('profile.store');
    Route::get('/mypage',[ProductController::class,'showMypage'])
    ->name('mypage');
    Route::get('/mypage/profile',[ProfileController::class,'edit'])
    ->name('profile.edit');
    Route::post('/mypage/profile',[ProfileController::class,'update'])
    ->name('profile.update');
    Route::get('/sell',[ProductController::class,'showListing'])
    ->name('listing');
    Route::post('/sell',[ProductController::class,'store'])
    ->name('listing.store');
    Route::get('/purchase/{item_id}',[ItemController::class,'showPurchase'])
    ->name('purchase');
    Route::post('/purchase/{item_id}',[ItemController::class,'purchase'])
    ->name('purchase.store');
    Route::post('/like/{product_id}',[ItemController::class,'toggleLike'])
    ->name('like.toggle');
    Route::post('/item/{item_id}/comment',[ItemController::class,'storeComment'])
    ->name('comment.store');
    Route::get('/purchase/address/{item_id}',[ProductController::class,'editAddress'])
    ->name('edit.address');
    Route::post('purchase/address/{item_id}',[ProductController::class,'updateAddress'])
    ->name('update.address');
});