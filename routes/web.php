<?php

use App\Livewire\Post\Edit;
use App\Livewire\Post\Index;
use App\Livewire\Post\Create;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagsController;
use App\Livewire\NotificationSweetAlert;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\PermissionController;
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
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::match(['get', 'post'], '/', [HomeController::class, 'index']);
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
    
    Route::get('detailpost/{seotitle}', [PostController::class, 'detail'])->name('posts.detail');
    Route::get('category/{seotitle}', [CategoriesController::class, 'detail'])->name('categories.detail');
    Route::post('comment/send/{seotitle}', [PostController::class, 'send'])->name('posts.send');
    Route::get('contact', [ContactController::class, 'show']);
    Route::post('contact/send', [ContactController::class, 'send'])->name('contact.send');

    // Subscribe 
    Route::post('/subscriber', [SubscriberController::class, 'store'])->name('subscribe');
    Route::get('/subscriber/verify/{token}/{email}', [SubscriberController::class, 'verify'])->name('subscriber_verify');

    Route::middleware([
        'auth:web',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {

        //Route::get('dashboard', function () { return view('admins.dashboard'); })->name('dashboard');
        Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
	    Route::get('dashboard/analytics', [HomeController::class, 'analitycs'])->name('dashboard.analitycs');
	    Route::get('forbidden', [HomeController::class, 'forbidden']);
        
        Route::resource('dashboard/contacts', ContactController::class);
        Route::post('dashboard/deleteallcontacts', [ContactController::class, 'deleteAll'])->name('contacts.deleteAll');
        Route::get('dashboard/contacts/{id}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
        Route::post('dashboard/contacts/reply/{id}', [ContactController::class, 'postReply'])->name('contacts.postReply');

        Route::resource('dashboard/users', UsersController::class);
        Route::post('dashboard/deleteallusers', [UsersController::class, 'deleteAll'])->name('users.deleteAll');
        
        Route::resource('dashboard/roles', RolesController::class);
        Route::post('dashboard/deleteallroles', [RolesController::class, 'deleteAll'])->name('roles.deleteAll');

        Route::resource('dashboard/permissions', PermissionController::class);
        Route::post('dashboard/deleteallpermissions', [PermissionController::class, 'deleteAll'])->name('permissions.deleteAll');

        Route::resource('dashboard/components', ComponentController::class);
        Route::post('dashboard/deleteallcomponents', [ComponentController::class, 'deleteAll'])->name('components.deleteAll');
        Route::post('dashboard/components/process-install', [ComponentController::class, 'processinstall'])->name('components.processInstall');

        Route::resource('dashboard/settings', SettingController::class);
        Route::post('dashboard/deleteallsettings', [SettingController::class, 'deleteAll'])->name('settings.deleteAll');
        Route::get('dashboard/settings', [SettingController::class, 'getGroups'])->name('settings.group');
        Route::get('dashboard/settings/sitemap', [SettingController::class, 'sitemap'])->name('settings.sitemap');
        
        Route::resource('dashboard/backups', BackupController::class);
        Route::get('dashboard/backups/download/{file_name}', [BackupController::class, 'download'])->name('backups.download');

        Route::resource('dashboard/menumanager', MenuController::class);
        Route::get('dashboard/menutable', [MenuController::class, 'getIndex'])->name('menumanager.menutable');
        Route::post('dashboard/menumanager/menusort', [MenuController::class, 'menusort'])->name('menumanager.menusort');
        Route::post('dashboard/deleteallmenu', [MenuController::class, 'deleteAll'])->name('menumanager.deleteAll');

        Route::resource('dashboard/themes', ThemeController::class);
        Route::get('dashboard/themes/active/{id}', [ThemeController::class, 'active'])->name('themes.active');
        Route::get('dashboard/themes/install', [ThemeController::class, 'install'])->name('themes.install');
        Route::post('dashboard/themes/process-install', [ThemeController::class, 'processinstall'])->name('themes.processInstall');
        
        // Route::get('dashboard/subscriber/all', [SubscriberController::class, 'index'])->name('all.subscribe');
        // Route::get('dashboard/subscriber/compose', [SubscriberController::class, 'compose'])->name('subscriber.compose');
        // Route::post('dashboard/subscriber/send', [SubscriberController::class, 'send_email_subscriber'])->name('subscriber.mail');
        Route::resource('dashboard/subscribers', SubscriberController::class);
        Route::post('dashboard/subscribers/send', [SubscriberController::class, 'composeMail'])->name('subscribers.compose');
        Route::post('dashboard/deleteallsubscribers', [SubscriberController::class, 'deleteAll'])->name('subscribers.deleteAll');
        
        Route::resource('dashboard/categories', CategoriesController::class);
        Route::post('dashboard/deleteallcategories', [CategoriesController::class, 'deleteAll'])->name('categories.deleteAll');
        
        Route::resource('dashboard/gallerys', GalleryController::class);
        Route::delete('dashboard/deleteallgallerys', [GalleryController::class, 'deleteAll'])->name('gallerys.deleteAll');
        
        Route::resource('dashboard/albums', AlbumController::class);
        Route::post('dashboard/deleteallalbums', [AlbumController::class, 'deleteAll'])->name('albums.deleteAll');
        Route::post('dashboard/albums/create-gallery', [AlbumController::class, 'createGallery'])->name('albums.createGallery');
        Route::post('dashboard/albums/delete-gallery', [AlbumController::class, 'deleteGallery'])->name('albums.deleteGallery');
        
        Route::resource('dashboard/posts', PostController::class);
        Route::post('dashboard/deleteallposts', [PostController::class, 'deleteAll'])->name('posts.deleteAll');
        Route::post('dashboard/posts/create-gallery', [PostController::class, 'createGallery'])->name('posts.createGallery');
        Route::post('dashboard/posts/delete-gallery', [PostController::class, 'deleteGallery'])->name('posts.deleteGallery');
        Route::post('dashboard/posts/send-subscriber/{id}', [PostController::class, 'sendSubscriber'])->name('posts.sendSubscriber');
        
        Route::resource('dashboard/pages', PagesController::class);
        Route::post('dashboard/deleteallpages', [PagesController::class, 'deleteAll'])->name('pages.deleteAll');
        
        Route::resource('dashboard/tags', TagsController::class);
        Route::get('dashboard/tags/get-tag', [TagsController::class, 'getTag'])->name('tags.getTag');
        Route::post('dashboard/deletealltags', [TagsController::class, 'deleteAll'])->name('tags.deleteAll');

        Route::get('dropzone', [HomeController::class, 'dropzone'])->name('home.dropzone');
        Route::post('dropzone/store', [HomeController::class, 'dropzoneStore'])->name('dropzone.store');

    });



