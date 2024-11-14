<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Livewire\ItemSellingForm;
use App\Livewire\ProfileForm;

//商品表示
Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/{item_id}', [ItemController::class, 'detail'])->name('item.detail');

//登録
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

//ログイン機能はFortifyServiceProviderによる

//以下auth

//商品販売
Route::get('/sell', [SellController::class, 'show'])->name('sell.show');
Route::post('/sell', [ItemSellingForm::class, 'save'])->name('sell.create');

//商品購入
Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');
Route::post('/purchase/{item_id}', [PurchaseController::class, 'create'])->name('purchase.create');

//住所変更
Route::get('/purchase/address/{item_id}', [UserProfileController::class, 'showAddress'])->name('address.show');
Route::post('/purchase/address/{item_id}', [UserProfileController::class, 'createAddress'])->name('address.create');

//マイページ
Route::get('/mypage', [MyPageController::class, 'show'])->name('mypage.show');

//プロフィール
Route::get('/mypage/profile', [UserProfileController::class, 'showProfile'])->name('profile.show');
Route::post('/mypage/profile', [ProfileForm::class, 'createProfile'])->name('profile.create');

//お気に入り
Route::post('/favorite/{item_id}', [FavoriteController::class, 'create'])->name('favorite');
Route::post('/unfavorite/{item_id}', [FavoriteController::class, 'delete'])->name('unfavorite');

//コメント
Route::post('/comment', [CommentController::class, 'create'])->name('comment.create');
Route::post('/comment/{comment_id}', [CommentController::class, 'delete'])->name('comment.delete');