<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PreorderProductController;
use App\Http\Controllers\Admin\ReturnPolicyController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerNotificationsController;
use App\Http\Controllers\CustomerOrdersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PushController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

// Web Push
Route::get('/push/latest', [PushController::class, 'latest'])->name('push.latest');
Route::middleware('auth')->group(function () {
    Route::post('/push/subscribe',   [PushController::class, 'subscribe'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [PushController::class, 'unsubscribe'])->name('push.unsubscribe');
});
Route::get('/returns', function () {
    return view('pages.returns', ['policy' => \App\Models\ReturnPolicy::current()]);
})->name('returns');
Route::view('/contact', 'pages.contact')->name('contact');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/track/{orderNumber}', [OrderController::class, 'track'])->name('orders.track');

// Customer auth
Route::get('/login',    [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [CustomerAuthController::class, 'login'])->name('customer.login.post');
Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
Route::post('/register',[CustomerAuthController::class, 'register'])->name('customer.register.post');
Route::post('/logout',  [CustomerAuthController::class, 'logout'])->name('customer.logout')->middleware('auth');

// Customer portal
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [CustomerOrdersController::class, 'index'])->name('customer.orders');
    Route::get('/my-notifications', [CustomerNotificationsController::class, 'index'])->name('customer.notifications');
    Route::post('/my-notifications/seen', [CustomerNotificationsController::class, 'markSeen'])->name('customer.notifications.seen');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('items', ItemController::class)->except(['show']);
        Route::resource('preorders', PreorderProductController::class)->except(['show']);
        Route::get('order-notifications', [AdminOrderController::class, 'notifications'])->name('orders.notifications');
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
        Route::get('return-policy', [ReturnPolicyController::class, 'edit'])->name('return-policy.edit');
        Route::put('return-policy', [ReturnPolicyController::class, 'update'])->name('return-policy.update');
    });
});
