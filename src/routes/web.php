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
use App\Http\Controllers\PaymentInstructionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\StripeWebhookController;
use App\Livewire\ItemSellingForm;
use App\Livewire\ProfileForm;
use App\Models\PurchasedItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

//商品表示
//indexページはキャッシュ不可
Route::middleware('cache.headers:no_store;max_age=0;etag')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('index');
});

Route::get('/item/{item_id}', [ItemController::class, 'detail'])->name('item.detail');
Route::get('/search', [ItemController::class, 'search'])->name('search');

//登録
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

//ログイン機能はFortifyServiceProviderによる

Route::middleware('auth')->group(function () {

    //商品販売
    Route::get('/sell', [SellController::class, 'show'])->name('sell.show');
    Route::post('/sell', [ItemSellingForm::class, 'save'])->name('sell.create');

    //商品購入
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'payment'])->name('payment.create');

    //住所変更
    Route::get('/purchase/address/{item_id}', [UserProfileController::class, 'showAddress'])->name('address.show');
    Route::post('/purchase/address/{item_id}', [UserProfileController::class, 'createAddress'])->name('address.create');

    //マイページ
    Route::get('/mypage', [MyPageController::class, 'show'])->name('mypage.show');

    //プロフィール
    Route::get('/mypage/profile', [UserProfileController::class, 'showProfile'])->name('profile.show');
    Route::post('/mypage/profile', [ProfileForm::class, 'save'])->name('profile.create');

    //お気に入り
    Route::post('/favorite/{item_id}', [FavoriteController::class, 'create'])->name('favorite');
    Route::post('/unfavorite/{item_id}', [FavoriteController::class, 'destroy'])->name('unfavorite');

    //コメント
    Route::post('/comment', [CommentController::class, 'create'])->name('comment.create');
    Route::post('/comment/{comment_id}', [CommentController::class, 'destroy'])->name('comment.destroy');

    //支払い手順表示
    Route::get('/thanks', [PurchaseController::class, 'showThanks'])->name('thanks');
    Route::get('/purchase/{item_id}/bank', [PaymentInstructionController::class, 'showBank'])->name('bank.show');
    Route::get('/purchase/{item_id}/konbini', [PaymentInstructionController::class, 'showKonbini'])->name('konbini.show');
});

//Webhook
Route::post('/webhook/stripe', [StripeWebhookController::class, 'handleWebhook'])->withoutMiddleware(ValidateCsrfToken::class);

//管理者用
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/dashboard/{user_id}', [AdminController::class, 'destroy'])->name('user.destroy');
});
