<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\HomeController;

use App\Http\Controllers\Admin\ReportController;

use App\Http\Controllers\Officer\ParkingController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'formLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'postLogin'])->name('postLogin');
});

Route::middleware(['auth'])->group(function () {
    // Route Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Route Logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware(['isAdmin'])->group(function () {
        // Route Report
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
        Route::get('/report/printReport', [ReportController::class, 'printReport'])->name('report.printReport');
    });

    Route::middleware(['isOfficer'])->group(function () {
        // Route Parkir Masuk
        Route::get('/parking-entrance', [ParkingController::class, 'getIn'])->name('parking.getIn');
        Route::post('/parking-entrance', [ParkingController::class, 'postIn'])->name('parking.postIn');

        // Route Parkir Keluar
        Route::get('/parking-out', [ParkingController::class, 'getOut'])->name('parking.getOut');
        Route::post('/parking-out', [ParkingController::class, 'postOut'])->name('parking.postOut');

        Route::get('/parking-getData', [ParkingController::class, 'getData'])->name('parking.getData');
    });
});
