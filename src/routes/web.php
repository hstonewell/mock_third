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

//商品表示
Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/{item_id}', [ItemController::class, 'detail'])->name('item.detail');

//登録
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

//ログイン機能はFortifyServiceProviderによる

//商品販売
Route::get('/sell', [SellController::class, 'show'])->name('show.sell');
Route::post('/sell', [SellController::class, 'create'])->name('sell');

//商品購入
Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('show.purchase');
Route::post('/purchase/{item_id}', [PurchaseController::class, 'create'])->name('purchase');

//住所変更
Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'show'])->name('show.address');
Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'create'])->name('change.address');

//マイページ
Route::get('/mypage', [MyPageController::class, 'show'])->name('show.mypage');

//プロフィール
Route::get('/mypage/profile', [UserProfileController::class, 'show'])->name('show.profile');
Route::post('/mypage/profile', [UserProfileController::class, 'create'])->name('edit.profile');

//お気に入り
Route::get('/favorite/{item_id}', [FavoriteController::class, 'create'])->name('favorite');
Route::post('/unfavorite/{item_id}', [FavoriteController::class, 'delete'])->name('unfavorite');

//コメント
Route::get('/comment', [CommentController::class, 'create'])->name('comment');
Route::post('/comment/{comment_id}', [CommentController::class, 'delete'])->name('delete.comment');