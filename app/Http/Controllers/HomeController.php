<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * نمایش لیست پست‌ها در صفحه اصلی با صفحه‌بندی (Pagination).
     */
    public function index()
    {
        // آخرین پست‌ها را دریافت و صفحه‌بندی می‌کنیم (مثلاً ۱۰ پست در هر صفحه)
        $posts = Post::latest()->paginate(10);
        
        // پست‌ها را به ویو home.index ارسال می‌کنیم
        return view('home.index', compact('posts'));
    }
}