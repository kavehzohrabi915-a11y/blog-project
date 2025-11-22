<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController; // برای کاربران عادی
use App\Http\Controllers\Admin\AdminPostController; // برای ادمین
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// ----------------------------------------------------------------------
// مسیرهای عمومی (PUBLIC ROUTES)
// ----------------------------------------------------------------------

// مسیر ریشه (صفحه اصلی) را به فهرست پست‌ها اختصاص می‌دهیم.
// این مسیر تعریف پیش‌فرض لاراول را بازنویسی می‌کند.
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// نمایش جزئیات یک پست با استفاده از slug
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');


// ----------------------------------------------------------------------
// مسیرهای احراز هویت (AUTHENTICATED & BREEZE ROUTES)
// ----------------------------------------------------------------------

// مسیر داشبورد (پیش‌فرض Breeze)


Route::get('/dashboard', function () {
    if (Auth::user()->is_admin) {
        return redirect()->route('admin.posts.index');
    }
    return view('dashboard'); // داشبورد معمولی برای کاربران عادی
})->middleware(['auth', 'verified'])->name('dashboard');

// مسیرهای مدیریت پروفایل کاربر (پیش‌فرض Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // مسیرهای مدیریت پست‌ها (بخش مدیریتی وبلاگ)
    // متدهای index و show حذف شدند، زیرا در مسیرهای عمومی تعریف شده‌اند.
    Route::resource('posts', PostController::class)->except(['index', 'show']);
});


// ----------------------------------------------------------------------
// بارگذاری مسیرهای احراز هویت Breeze
// ----------------------------------------------------------------------
require __DIR__.'/auth.php';


// ----------------------------------------------------------------------
// مسیرهای پنل ادمین (ADMIN PANEL)
// ----------------------------------------------------------------------
Route::prefix('admin')
     ->middleware(['auth', 'admin'])
     ->name('admin.')
     ->group(function () {
         Route::resource('posts', AdminPostController::class);
     });