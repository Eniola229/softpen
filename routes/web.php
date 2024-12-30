<?php

use App\Http\Controllers\ProfileController;
//ADMIN
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\SchoolController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


//ADMIN 
Route::get('admin/login', [AdminAuthController::class, 'index'])->name('admin/login');
Route::post('post/login', [AdminAuthController::class, 'postLogin'])->name('admin-login.post'); 

//AUTH ADMIN
Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin-dashboard');
    Route::get('/schools', [SchoolController::class, 'index'])->name('admin/schools');
    Route::post('/schools', [SchoolController::class, 'create'])->name('admin/schools');
    Route::get('add/schools', [SchoolController::class, 'addSchool'])->name('admin/add/schools');
    Route::get('view/schools/{id}', [SchoolController::class, 'view'])->name('admin/view/schools');
    Route::get('/change/{id}', [SchoolController::class, 'changeStatus'])->name('admin/changeStatus');


    Route::get('logout', [AdminAuthController::class, 'logout'])->name('admin/logout');
});