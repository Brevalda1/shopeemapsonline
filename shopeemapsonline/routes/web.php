<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;

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
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboardadmin', [App\Http\Controllers\DashboardController::class, 'adminDashboard'])->name('dashboardadmin');

Route::post('/pins', [App\Http\Controllers\PinController::class, 'store'])->name('pins.store');
Route::delete('/pins/{id}', [App\Http\Controllers\PinController::class, 'destroy'])->name('pins.destroy');
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



// Route untuk menampilkan semua pengguna
Route::get('/usersindex', [UserController::class, 'index'])->name('users.index');

// Route untuk menampilkan form tambah pengguna
Route::get('/userscreate', [UserController::class, 'create'])->name('users.create');

// Route untuk menyimpan data pengguna baru
Route::post('/usersstore', [UserController::class, 'store'])->name('users.store');

// Route untuk menampilkan form edit pengguna
Route::get('/usersedit/{id}', [UserController::class, 'edit'])->name('users.edit');

// Route untuk memperbarui data pengguna
Route::put('/usersupdate/{id}', [UserController::class, 'update'])->name('users.update');

// Route untuk menghapus data pengguna
Route::delete('/usersdelete/{id}', [UserController::class, 'destroy'])->name('users.destroy');


