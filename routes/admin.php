<?php

use App\Http\Controllers\Admin\AcademicSessionController;
use App\Http\Controllers\Admin\AdmissionController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin authentication
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |----------------------------------------------------------------------
    | Admin panel (protected)
    |----------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {
        Route::redirect('/', '/admin/dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Students
        Route::resource('students', StudentController::class);

        // Teachers
        Route::resource('teachers', TeacherController::class);

        // Classes + sections
        Route::resource('classes', SchoolClassController::class)->except('show');
        Route::post('classes/{class}/sections', [SchoolClassController::class, 'storeSection'])->name('classes.sections.store');
        Route::delete('sections/{section}', [SchoolClassController::class, 'destroySection'])->name('sections.destroy');

        // Subjects
        Route::resource('subjects', SubjectController::class)->except('show');

        // Attendance
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');

        // Academic sessions
        Route::get('sessions', [AcademicSessionController::class, 'index'])->name('sessions.index');
        Route::post('sessions', [AcademicSessionController::class, 'store'])->name('sessions.store');
        Route::put('sessions/{session}', [AcademicSessionController::class, 'update'])->name('sessions.update');
        Route::delete('sessions/{session}', [AcademicSessionController::class, 'destroy'])->name('sessions.destroy');

        // Exams
        Route::post('exams/{exam}/publish', [ExamController::class, 'togglePublish'])->name('exams.publish');
        Route::post('exams/{exam}/subjects', [ExamController::class, 'storeSubject'])->name('exams.subjects.store');
        Route::delete('exam-subjects/{examSubject}', [ExamController::class, 'destroySubject'])->name('exam-subjects.destroy');
        Route::resource('exams', ExamController::class);

        // Results
        Route::post('results/publish', [ResultController::class, 'publish'])->name('results.publish');
        Route::get('results', [ResultController::class, 'index'])->name('results.index');
        Route::get('results/entry', [ResultController::class, 'create'])->name('results.create');
        Route::post('results/entry', [ResultController::class, 'store'])->name('results.store');
        Route::get('results/{result}/edit', [ResultController::class, 'edit'])->name('results.edit');
        Route::put('results/{result}', [ResultController::class, 'update'])->name('results.update');
        Route::delete('results/{result}', [ResultController::class, 'destroy'])->name('results.destroy');

        // Notices
        Route::post('notices/{notice}/publish', [NoticeController::class, 'togglePublish'])->name('notices.publish');
        Route::resource('notices', NoticeController::class)->except(['show']);

        // Events
        Route::post('events/{event}/publish', [EventController::class, 'togglePublish'])->name('events.publish');
        Route::resource('events', EventController::class)->except(['show']);

        // News
        Route::post('news/{news}/publish', [NewsController::class, 'togglePublish'])->name('news.publish');
        Route::resource('news', NewsController::class)->except(['show']);

        // Gallery
        Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');
        Route::post('gallery', [GalleryController::class, 'store'])->name('gallery.store');
        Route::get('gallery/{album}', [GalleryController::class, 'show'])->name('gallery.show');
        Route::put('gallery/{album}', [GalleryController::class, 'update'])->name('gallery.update');
        Route::delete('gallery/{album}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
        Route::post('gallery/{album}/images', [GalleryController::class, 'uploadImages'])->name('gallery.images.store');
        Route::delete('gallery-images/{image}', [GalleryController::class, 'destroyImage'])->name('gallery.images.destroy');

        // Admissions
        Route::get('admissions', [AdmissionController::class, 'index'])->name('admissions.index');
        Route::get('admissions/{admission}', [AdmissionController::class, 'show'])->name('admissions.show');
        Route::put('admissions/{admission}/status', [AdmissionController::class, 'updateStatus'])->name('admissions.status');
        Route::delete('admissions/{admission}', [AdmissionController::class, 'destroy'])->name('admissions.destroy');

        // Contact messages
        Route::get('messages', [ContactController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [ContactController::class, 'show'])->name('messages.show');
        Route::post('messages/{message}/read', [ContactController::class, 'toggleRead'])->name('messages.read');
        Route::delete('messages/{message}', [ContactController::class, 'destroy'])->name('messages.destroy');

        // Settings
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
