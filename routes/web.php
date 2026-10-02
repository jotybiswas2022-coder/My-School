<?php

use App\Http\Controllers\Frontend\AdmissionController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\NoticeController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ResultController;
use App\Http\Controllers\Frontend\TeacherController;
use App\Http\Controllers\Student\AuthController as StudentAuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Language switcher (English / বাংলা)
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/principal', [PageController::class, 'principal'])->name('principal');
Route::get('/academics', [PageController::class, 'academics'])->name('academics');
Route::get('/classes', [PageController::class, 'classes'])->name('classes');
Route::get('/subjects', [PageController::class, 'subjects'])->name('subjects');
Route::get('/facilities', [PageController::class, 'facilities'])->name('facilities');
Route::get('/students', [PageController::class, 'students'])->name('students');
Route::get('/admission/information', [PageController::class, 'admissionsInfo'])->name('admission.info');

Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers');
Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');

Route::get('/notices', [NoticeController::class, 'index'])->name('notices');
Route::get('/notices/{notice}', [NoticeController::class, 'show'])->name('notices.show');

Route::get('/events', [EventController::class, 'index'])->name('events');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

Route::get('/news', [NewsController::class, 'index'])->name('news');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/gallery/{album}', [GalleryController::class, 'show'])->name('gallery.show');

Route::get('/results', [ResultController::class, 'index'])->name('results');
Route::post('/results/search', [ResultController::class, 'search'])->name('results.search');

Route::get('/admission', [AdmissionController::class, 'index'])->name('admission');
Route::post('/admission', [AdmissionController::class, 'store'])->name('admission.store');
Route::get('/admission/status/{application?}', [AdmissionController::class, 'statusForm'])->name('admission.status');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.page');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Student authentication
|--------------------------------------------------------------------------
*/

Route::get('/student/login', [StudentAuthController::class, 'showLogin'])->name('student.login');
Route::post('/student/login', [StudentAuthController::class, 'login'])->name('student.login.attempt');
Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');

/*
|--------------------------------------------------------------------------
| Student portal (authenticated)
|--------------------------------------------------------------------------
*/

Route::prefix('student')->name('student.')->middleware('auth:student')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('profile');
    Route::get('/attendance', [StudentDashboardController::class, 'attendance'])->name('attendance');
    Route::get('/results', [StudentDashboardController::class, 'results'])->name('results');
    Route::get('/notices', [StudentDashboardController::class, 'notices'])->name('notices');
    Route::get('/academics', [StudentDashboardController::class, 'academics'])->name('academics');
});

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/admin.php';
