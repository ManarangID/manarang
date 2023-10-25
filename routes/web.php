<?php

use App\Livewire\Post\Edit;
use App\Livewire\Post\Index;
use App\Livewire\Post\Create;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\SubscriberController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('frontend.canvas.home');
// });
    Route::get('/', [HomeController::class, 'show'])->name('home');
    
    // Subscribe 
    Route::post('/subscriber', [SubscriberController::class, 'store'])->name('subscribe');
    Route::get('/subscriber/verify/{token}/{email}', [SubscriberController::class, 'verify'])->name('subscriber_verify');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () { return view('admins.dashboard'); })->name('dashboard');
    
    Route::get('/subscriber/all', [SubscriberController::class, 'index'])->name('all.subscribe');
    Route::get('/subscriber/compose', [SubscriberController::class, 'compose'])->name('subscriber.compose');
    Route::post('/subscriber/send', [SubscriberController::class, 'send_email_subscriber'])->name('subscriber.mail');

    Route::get('settings', [SettingController::class, 'index'])->name('settings');
    Route::get('settings/{id}/edit', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings/{id}', [SettingController::class, 'update'])->name('settings.update');

    Route::get('pages', [PagesController::class, 'index'])->name('pages');    
    Route::post('pages', [PagesController::class, 'store'])->name('pages.store');
    Route::get('pages/create', [PagesController::class, 'create'])->name('pages.create');
    Route::get('pages/{id}/edit', [PagesController::class, 'edit'])->name('pages.edit');
    Route::put('pages/{id}', [PagesController::class, 'update'])->name('pages.update');
    Route::post('pages/{seotitle}', [PagesController::class, 'likes'])->name('pages.likes');
    Route::delete('pages/{id}', [PagesController::class, 'destroy'])->name('pages.destroy');
    
    Route::get('posts', [PostController::class, 'index'])->name('posts');    
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::get('posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('posts/{id}', [SubscriberController::class, 'send_new_post'])->name('posts.subscriber');
    Route::post('posts/{seotitle}', [PostController::class, 'likes'])->name('posts.likes');

    Route::get('categories', [CategoriesController::class, 'index'])->name('categories');    
    Route::post('categories', [CategoriesController::class, 'store'])->name('categories.store');
    Route::get('categories/create', [CategoriesController::class, 'create'])->name('categories.create');
    Route::get('categories/{id}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{id}', [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('categories/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');
    
    Route::get('gallerys', [GalleryController::class, 'index'])->name('gallerys');    
    Route::post('gallerys', [GalleryController::class, 'store'])->name('gallerys.store');
    Route::get('gallerys/create', [GalleryController::class, 'create'])->name('gallerys.create');
    Route::get('gallerys/{id}/edit', [GalleryController::class, 'edit'])->name('gallerys.edit');
    Route::put('gallerys/{id}', [GalleryController::class, 'update'])->name('gallerys.update');
    Route::get('gallerys/{seotitle}', [GalleryController::class, 'show'])->name('gallerys.show');
    Route::delete('gallerys/{id}', [GalleryController::class, 'destroy'])->name('gallerys.destroy');
    
    Route::get('albums', [AlbumController::class, 'index'])->name('albums');    
    Route::post('albums', [AlbumController::class, 'store'])->name('albums.store');
    Route::get('albums/create', [AlbumController::class, 'create'])->name('albums.create');
    Route::get('albums/{id}/edit', [AlbumController::class, 'edit'])->name('albums.edit');
    Route::put('albums/{id}', [AlbumController::class, 'update'])->name('albums.update');
    Route::get('albums/{seotitle}', [AlbumController::class, 'show'])->name('albums.show');
    Route::delete('albums/{id}', [AlbumController::class, 'destroy'])->name('albums.destroy');
    

});
Route::fallback(function () {
    return redirect('/');
});
Route::controller(GoogleController::class)->group(function(){
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});
Route::controller(GoogleController::class)->group(function(){
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});
Route::get('posts/{seotitle}', [PostController::class, 'show'])->name('posts.show');
Route::get('pages/{seotitle}', [PagesController::class, 'detail'])->name('pages.detail');
