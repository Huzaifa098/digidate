<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchingController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Models\Matching;

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

// Home and contact pages
Route::get('/', [HomeController::class , 'index'])->name('home');
Route::resource('contact', ContactController::class)->only(['create' , 'store']);

// Custom pages
Route::get('/over-ons', function(){return view('master.about_us');})->name('about-us');
Route::get('/voorwaarden', function(){return view('master.terms');})->name('terms');

// Password forget
Route::get('forget-password', [PasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');

// Reset password profile
Route::get('reset-password/{token}', [PasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [PasswordController::class, 'submitResetPassword'])->name('reset.password.post');
Route::post('reset-password-form', [PasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post.form');

// Guest
Route::group(['middleware' => 'guest'], function () {
    Route::post('/', [AuthController::class , 'login'])->name('login');
    Route::get('/register', [AuthController::class , 'register_create'])->name('register');
    Route::post('/register', [AuthController::class , 'store'])->name('register.store');
});

// Logout
Route::group(['middleware' => ['auth', 'prevent-back-history']], function () {
    Route::get('/logout', [AuthController::class , 'logout'])->name('logout');

    // Profile - images
    Route::get('/setImageToPrimary/{id}', [ProfileController::class , 'setImageToPrimary'])->name('setImageToPrimary');
   Route::get('/deleteImage/{id}', [ProfileController::class , 'deleteImage'])->name('delete.images');

    // Profile - update
    Route::put('/updateProfile', [ProfileController::class , 'updateProfile'])->name('update.profile');
    Route::put('/updatePersonal', [ProfileController::class , 'updatePersonal'])->name('update.personal');
    Route::put('/updatePref', [ProfileController::class , 'updatePref'])->name('update.pref');

    // Msges readed
    Route::post('msgReaded/{id}', [MatchingController::class , 'msgReaded'])->name('msgReaded');

    // Edit email
    Route::get('reset-email-form', [ProfileController::class, 'showEmailEditForm'])->name('reset.email.form');
    Route::post('reset-email-post', [ProfileController::class, 'emailEditSubmit'])->name('reset.email.post');

});

// Loggen in users + Admin as user
Route::group(['middleware' => ['auth', 'prevent-back-history', 'user']], function () {
    // Pages
    Route::get('/dashboard', [HomeController::class , 'dashboard'])->name('dashboard');
    Route::get('/settings', [ProfileController::class , 'index'])->name('profileUser');
    Route::get('/matching', [MatchingController::class , 'index'])->name('matching');
    Route::get('/chat', [MatchingController::class , 'indexChat'])->name('chat');

    // One time
    Route::post('/register_voltooien', [AuthController::class , 'register_finish_submit'])->name('register.finish');

    // Like and dislike
    Route::post('/like/{id}', [MatchingController::class , 'like'])->name('like');
    Route::post('/dislike/{id}', [MatchingController::class , 'dislike'])->name('dislike');

    // Msg
    Route::post('/send/{id}', [MatchingController::class , 'sendMsg'])->name('send.msg');

    // Delete user self
    Route::delete('/userDelete', [ProfileController::class , 'destroy'])->name('user.delete');
});



Route::group(['middleware' => ['admin', 'prevent-back-history']], function () {
    Route::get('/dashboardAdmin', [HomeController::class , 'dashboardAdmin'])->name('dashboardAdmin');
    Route::resource('contact', ContactController::class)->only(['index','show','destroy']);
    Route::resource('users', AuthController::class)->only(['index','destroy','update','edit']);
    Route::resource('tags', TagController::class);

    // Switch
    Route::get('/switchTo', [AuthController::class , 'switchTo'])->name('switchTo');

    // Profile
    Route::get('/setting', [ProfileController::class , 'index'])->name('profile');
});
