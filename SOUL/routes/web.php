<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Manager\SectionController;
use App\Http\Controllers\Manager\UserController;
use App\Http\Controllers\Manager\ReportController;
use App\Http\Controllers\Admin\Dashboardcontroller;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\PostReportController;
use App\Http\Controllers\Member\PostCreateController;
use App\Http\Controllers\Member\FeedController;

Route::get('/', function () {
    return view('home');
});

// Register
Route::get('/register', [RegisterController::class, 'show'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Login
Route::get('/login', [LoginController::class, 'show'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::post('/logout', function () {
    auth()->logout();
    return redirect()->route('login');
})->name('logout');


//  MANAGER ONLY
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'role:manager'])->group(function () {


    Route::get('/sections', [SectionController::class, 'index'])->name('sections');
    Route::post('/sections', [SectionController::class, 'store'])->name('sections.store');
    Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
    Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users/{user}/promote', [UserController::class, 'promote'])->name('users.promote');
    Route::post('/users/{user}/demote', [UserController::class, 'demote'])->name('users.demote');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');

    Route::post('/reports/{report}/review', [ReportController::class, 'review'])
        ->name('reports.review');

    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])
        ->name('reports.destroy');

    Route::post('/reports/{report}/resolve', function (App\Models\AdminReport $report) {
        $report->update([
            'status' => 'reviewed',
            'reviewed_at' => now(),
        ]);
        return back()->with('success', 'Report marked as resolved.');
    })->name('reports.resolve');


});



//  MEMBER ONLY
Route::prefix('member')->name('member.')->middleware(['auth', 'role:member'])->group(function () {

    Route::get('/gallery', function () {
        return view('member.gallery');
    })->name('gallery');

});


//  ACTIVE MEMBER ONLY
Route::prefix('activemember')->name('activemember.')->middleware(['auth', 'role:member', 'status:active'])->group(function () {

    Route::get ('/createpost', [PostCreateController::class, 'create'])->name('posts.create');
    Route::post('/createpost', [PostCreateController::class, 'store'] )->name('posts.store');

    Route::get ('/feed',                 [FeedController::class, 'index']  )->name('feed');
    Route::post('/feed/{post}/like',     [FeedController::class, 'like']   )->name('feed.like');
    Route::post('/feed/{post}/comment',  [FeedController::class, 'comment'])->name('feed.comment');
    Route::post('/feed/{post}/report',   [FeedController::class, 'report'] )->name('feed.report');
});


// ADMIN ONLY
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin-dashboard', [Dashboardcontroller::class, 'index'])->name('admindashboard');
    Route::post('/admin-dashboard/{user}/ban', [Dashboardcontroller::class, 'ban'])->name('admindashboard.ban');
    Route::post('/admin-dashboard/{user}/activate', [Dashboardcontroller::class, 'activate'])->name('admindashboard.activate');
    Route::post('/admin-dashboard/{user}/approve', [Dashboardcontroller::class, 'approve'])->name('admindashboard.approve');


    Route::get('/posts', [PostController::class, 'index'])->name('posts');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/approve', [PostController::class, 'approve'])->name('posts.approve');

    Route::get ('/reported-posts',                    [PostReportController::class, 'index']     )->name('reported-posts');
    Route::post('/reported-posts/{report}/keep',      [PostReportController::class, 'keep']      )->name('reported-posts.keep');
    Route::post('/reported-posts/{report}/delete-post',[PostReportController::class, 'deletePost'])->name('reported-posts.delete-post');

    Route::get('/admin/reports', [AdminReportController::class, 'index'])->name('reports');
    Route::post('/admin/reports', [AdminReportController::class, 'store'])->name('reports.store');




});