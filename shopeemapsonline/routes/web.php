<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MembershipController;

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




// Route::resource('pengguna', PenggunaController::class);
// Route::post('/pins', [PinController::class, 'store'])->name('pins.store');

// Route::get('/pins', [PinController::class, 'index'])->name('pins.index');
// Route::delete('/pins/{id}', [PinController::class, 'destroy'])->name('pins.destroy');
Route::get('/', function () {
    return view('welcome');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboardadmin', [App\Http\Controllers\DashboardController::class, 'adminDashboard'])->name('dashboardadmin');

// Add new route for dashboard2
Route::get('/pengguna/dashboard2', function () {
    return view('pengguna.dashboard2');
})->name('pengguna.dashboard2');

Route::post('/pins', [App\Http\Controllers\PinController::class, 'store'])->name('pins.store');
Route::delete('/pins/{id}', [App\Http\Controllers\PinController::class, 'destroy'])->name('pins.destroy');
Route::put('/pins/{id}', [App\Http\Controllers\PinController::class, 'update'])->name('pins.update');
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->name('logout');
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'create'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'store']);

// // Rute untuk admin
// Route::get('/admin/dashboard', function () {
//     return view('admin.dashboard');
// })->name('admin.dashboard');

// // Rute untuk pengguna biasa
// Route::get('/pengguna/dashboard', function () {
//     return view('pengguna.dashboard');
// })->name('pengguna.dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/pins', [PinController::class, 'store'])->name('pins.store');
Route::delete('/pins/{id}', [PinController::class, 'destroy'])->name('pins.destroy');

// Routes untuk pins
Route::get('/pins', [PinController::class, 'index'])->name('pins.index');
Route::post('/pins', [PinController::class, 'store'])->name('pins.store');
Route::delete('/pins/{pin}', [PinController::class, 'destroy'])->name('pins.destroy');


Route::get('/pengguna', [UserController::class, 'index'])->name('pengguna.index');
Route::get('/pengguna/create', [UserController::class, 'create'])->name('pengguna.create');
Route::post('/pengguna', [UserController::class, 'store'])->name('pengguna.store');
Route::get('/pengguna/{id}/edit', [UserController::class, 'edit'])->name('pengguna.edit');
Route::put('/pengguna/{id}', [UserController::class, 'update'])->name('pengguna.update');
Route::delete('/pengguna/{id}', [UserController ::class, 'destroy'])->name('pengguna.destroy');

Route::post('/payment/callback', [RegisterController::class, 'paymentCallback'])->name('payment.callback');
Route::post('/payment/token', [RegisterController::class, 'getPaymentToken'])->name('payment.token');

// Route untuk notifikasi dari Midtrans
Route::post('/payment/notification', [RegisterController::class, 'notificationHandler'])->name('payment.notification');

// Route untuk handle success payment dari frontend
Route::post('/payment/success', [RegisterController::class, 'handlePaymentSuccess'])->name('payment.success');

// Membership payment routes
Route::post('/membership/payment/token', [MembershipController::class, 'getPaymentToken'])
    ->name('membership.payment.token');
Route::post('/membership/payment/success', [MembershipController::class, 'handlePaymentSuccess'])
    ->name('membership.payment.success');
Route::post('/membership/payment/notification', [MembershipController::class, 'notificationHandler'])
    ->name('membership.payment.notification');
