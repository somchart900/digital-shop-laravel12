<?php

use Illuminate\Support\Facades\Route;

// start public group ----------------------------------------------------------------
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\ItemController;

Route::group([], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/หมวดหมู่สินค้า', [CategoryController::class, 'index'])->name('category');
    Route::get('/หมวดหมู่/{category_name}', [ProductController::class, 'index'])->name('product');
    Route::get('/หมวดหมู่/{category_name}/สินค้า/{product_name}', [ItemController::class, 'index'])->name('item');
    Route::post('/orderadd', [ItemController::class, 'add'])->name('orderadd');
});
// end public group ----------------------------------------------------------------

// start auth group ----------------------------------------------------------------
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\FileController;

Route::prefix('auth')->name('auth.')->group(function () {
    //เริ่มต้น-เฉพาะที่ยังไม่ได้ login เเละยังไม่ได้ register เท่านั้น 
    Route::middleware('guest')->group(function () {
        Route::get('/register', [RegisterController::class, 'index'])->name('register');
        Route::post('/register-process', [RegisterController::class, 'registerProcess'])->middleware('rate_limit')->name('register-process');
        Route::get('/login', [LoginController::class, 'index'])->name('login');
        Route::post('/login-process', [LoginController::class, 'loginProcess'])->middleware('rate_limit')->name('login-process');

        Route::get('/forget-password', [ForgetPasswordController::class, 'index'])->name('forget-password');
        Route::post('/forget-password-process', [ForgetPasswordController::class, 'forgetPasswordProcess'])->name('forget-password-process');
        Route::get('/reset-password', [ResetPasswordController::class, 'index'])->name('reset-password');
        Route::post('/reset-password-process', [ResetPasswordController::class, 'resetPasswordProcess'])->name('reset-password-process');
    });
    //สิ้นสุด-เฉพาะที่ยังไม่ได้ login เเละยังไม่ได้ register เท่านั้น
    //--------------------------------------------------------------------------------
    //เริ่มต้น-เฉพาะที่ login แล้ว เท่านั้น
    Route::middleware('member')->group(function () {
        Route::post('/logout', LogoutController::class)->name('logout');
        Route::post('/change-password', ChangePasswordController::class)->name('change-password');
        Route::get('/verification', [VerificationController::class, 'index'])->name('verification');
        Route::post('/verification-request', [VerificationController::class, 'verificationRequest'])->name('verification-request');
        Route::get('/verification-otp', [VerificationController::class, 'verificationOtp'])->name('verification-otp');
        Route::post('/verification-process', [VerificationController::class, 'verificationProcess'])->name('verification-process');
        Route::get('/files/{filename}', [FileController::class, 'download'])->name('files.download');
    });
    //สิ้นสุด-เฉพาะที่ login แล้ว เท่านั้น
});
// end auth group --------------------------------------------------------------------------------


// start แอดมิน group -----------------------------------------------------------------------------
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\User\ShowController;
use App\Http\Controllers\Admin\User\DeleteController;
use App\Http\Controllers\Admin\User\LevelController;
use App\Http\Controllers\Admin\User\CreditController;
use App\Http\Controllers\Admin\User\InboxController as AdminInboxController;
use App\Http\Controllers\Admin\Setting\PaymentController;
use App\Http\Controllers\Admin\Setting\WebController;
use App\Http\Controllers\Admin\Setting\ApiController;
use App\Http\Controllers\Admin\Setting\OtherController;
use App\Http\Controllers\Admin\Product\ProductController as AdminProductController;
use App\Http\Controllers\Admin\Product\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\Product\ItemController as AdminItemController;
use App\Http\Controllers\Admin\Setting\SettingController;
use App\Http\Controllers\Admin\ReportController;

Route::prefix('admin')->name('admin.')->group(function () {
    //เริ่มต้น--เฉพาะสมาชิก เท่านั้น
    Route::middleware('member')->group(function () {
        //-----------dashboard
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        //-----------user zone
        Route::get('/user', ShowController::class)->name('user');
        //-----------inbox zone
        Route::get('/inbox', [AdminInboxController::class, 'index'])->name('inbox');
        //-----------setting zone
        Route::get('/setting/web', [WebController::class, 'index'])->name('setting.web');
        Route::get('/setting/payment', [PaymentController::class, 'index'])->name('setting.payment');
        Route::get('/setting/api', [ApiController::class, 'index'])->name('setting.api');
        Route::get('/setting/other', [OtherController::class, 'index'])->name('setting.other');
        Route::get('/setting/category', [AdminCategoryController::class, 'index'])->name('setting.category');
        Route::get('/setting/product/{category_id}', [AdminProductController::class, 'index'])->name('setting.product');
        Route::get('/setting/item/{category_id}/{product_id}', [AdminItemController::class, 'index'])->name('setting.item');
        Route::get('/report', ReportController::class)->name('report');
    });
    //สิ้นสุด ขอบเขต-เฉพาะสมาชิก เท่านั้น
    //--------------------------------
    //เริ่มต้น--เฉพาะแอดมิน เท่านั้น
    Route::middleware('admin')->group(function () {
        //-----------user zone
        Route::post('/user/update/level', LevelController::class)->name('user.update.level');
        Route::post('/user/update/credit', CreditController::class)->name('user.update.credit');
        Route::post('/user/delete', DeleteController::class)->name('user.delete');
        //-----------setting zone
        Route::post('/setting/check_credit_byshop', [SettingController::class, 'check_credit_byshop'])->name('setting.check_credit_byshop');
        Route::post('/setting/update', [SettingController::class, 'updateOrCreate'])->name('setting.update');
        Route::post('/setting/delete', [SettingController::class, 'delete'])->name('setting.delete');
        Route::post('/setting/category/create', [AdminCategoryController::class, 'create'])->name('setting.category.create');
        Route::post('/setting/category/delete', [AdminCategoryController::class, 'delete'])->name('setting.category.delete');
        Route::post('/setting/item/create', [AdminItemController::class, 'create'])->name('setting.item.create');
        Route::post('/setting/item/delete', [AdminItemController::class, 'delete'])->name('setting.item.delete');
        Route::post('/setting/product/create', [AdminProductController::class, 'create'])->name('setting.product.create');
        Route::post('/setting/product/delete', [AdminProductController::class, 'delete'])->name('setting.product.delete');
    });
    //สิ้นสุด ขอบเขต-เฉพาะแอดมิน เท่านั้น
});
// end admin group -----------------------------------------------------------------------


// start user group -----------------------------------------------------------------------
use App\Http\Controllers\User\InboxController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\OrderListController;
use App\Http\Controllers\User\OrderDetailController;
use App\Http\Controllers\User\WalletController;
//เริ่มต้น-เฉพาะสมาชิก เท่านั้น
Route::middleware('member')->prefix('user')->name('user.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/topup', [WalletController::class, 'index'])->name('topup');
    Route::post('/topup/redeem', [WalletController::class, 'redeem'])->name('topup.redeem');
    Route::post('/topup/checkslip', [WalletController::class, 'checkslip'])->name('topup.checkslip');
    Route::get('/order-list', [OrderListController::class, 'index'])->name('order.list');
    Route::get('/order-detail/{id}', [OrderDetailController::class, 'index'])->name('order.detail');
    Route::get('/inbox', [InboxController::class, 'index'])->name('inbox');
});
//สิ้นสุด ขอบเขต-เฉพาะสมาชิก เท่านั้น
// end user group -----------------------------------------------------------------------
