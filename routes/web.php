<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models;
use App\Http\Controllers;

Route::group(['prefix' => 'auth'], function(){
    Route::get('/login', [Controllers\AuthController::class, 'Login'])->name('login');
    Route::post('/login', [Controllers\AuthController::class, 'PostLogin'])->name('login.post');
    Route::post('/logout', [Controllers\AuthController::class, 'PostLogout'])->name('logout');
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('/', [Controllers\DashboardController::class, 'Dashboard'])->name('dashboard');

    Route::get('/dashboard', function(Request $req){
        return response()->view('pages.dashboard');
    })->name('dashboard.dash');
    
    // banner
    Route::group(['prefix' => 'banner'], function(){
        Route::get("/", [Controllers\BannerController::class, 'Banner'])->name('banner.list');
        Route::post("/", [Controllers\BannerController::class, 'BannerCreate'])->name('banner.create');
        Route::delete("/delete/{id}", [Controllers\BannerController::class, 'BannerDelete'])->name('banner.delete');
        Route::put("/edit/{id}", [Controllers\BannerController::class, 'BannerEdit'])->name('banner.edit');
    });

    // product
    Route::group(['prefix' => 'product'], function(){
        Route::get("/", [Controllers\ProductController::class, 'Product'])->name('product.list');
        Route::post("/", [Controllers\ProductController::class, 'ProductCreate'])->name('product.create');
        Route::put("/edit/{id}", [Controllers\ProductController::class, 'ProductEdit'])->name('product.edit');
        Route::post("/delete/{id}", [Controllers\ProductController::class, 'ProductDelete'])->name('product.delete');
        Route::post('/image/add/{id}', [Controllers\ProductController::class, 'ProductAddImage'])->name('product.image.add');
        Route::post('/image/delete/{id}', [Controllers\ProductController::class, 'ProductDeleteImage'])->name('product.image.delete');
        Route::put("/toggle/event/{id}", [Controllers\ProductController::class, 'ProductToggleEvent'])->name('product.toggle.event');
        Route::put("/toggle/package/{id}", [Controllers\ProductController::class, 'ProductTogglePackage'])->name('product.toggle.package');
    });
    
    // transaksi
    Route::group(['prefix' => 'transaction'], function(){
       Route::get("/", [Controllers\TransactionController::class, 'Transaction'])->name('transaction.list'); 
    });

    // users
    Route::group(['prefix' => 'users'], function(){
        Route::get("/", [Controllers\UsersController::class, 'Users'])->name('users.list');
        Route::post("/", [Controllers\UsersController::class, 'UsersCreate'])->name('users.create');
        Route::put("/edit/{id}", [Controllers\UsersController::class, 'UsersEdit'])->name('users.edit');
        Route::post("/delete/{id}", [Controllers\UsersController::class, 'UsersDelete'])->name('users.delete');
    });
});