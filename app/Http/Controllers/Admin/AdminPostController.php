<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // برای استفاده در صورت نیاز به تولید slug

class AdminPostController extends Controller
{
    /**
     * نمایش لیست تمام پست‌ها در پنل ادمین.
     */
    public function index()
    {
        // نمایش همه پست‌ها برای ادمین (شامل منتشر نشده‌ها)
        $posts = Post::with('user')->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * نمایش فرم ایجاد پست جدید.
     */
    public function create()
    {
        // در اینجا می‌توانید دسته‌بندی‌ها و تگ‌ها را ارسال کنید
        return view('admin.posts.create');
    }

    /**
     * ذخیره پست جدید و هدایت به صفحه اصلی عمومی.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:posts,slug',
            'content' => 'required',
        ]);

        // ایجاد پست، تنظیم user_id و انتشار فوری (published_at = now())
       $post = Post::create($request->only(['title', 'slug', 'content']) + [
    'user_id' => auth()->id(),
    'published_at' => now(), // ادمین پست را منتشر می‌کند
]);

        
        // ----------------------------------------------------
        // هدایت به صفحه اصلی عمومی (مطابق با درخواست شما)
        // ----------------------------------------------------
        return redirect()->route('posts.index')->with('success', 'پست با موفقیت ایجاد و منتشر شد.');
    }

    /**
     * نمایش جزئیات یک پست (معمولاً در ادمین برای پیش‌نمایش استفاده می‌شود).
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        // اگر تمایل به استفاده از ویوی عمومی posts.show دارید:
        // return view('posts.show', compact('post'));
        
        // اگر تمایل به ویوی ادمین دارید:
        return view('admin.posts.show', compact('post'));
    }

    /**
     * نمایش فرم ویرایش پست.
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        // در اینجا می‌توانید دسته‌بندی‌ها و تگ‌های موجود را ارسال کنید
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * به‌روزرسانی پست موجود و هدایت به صفحه اصلی عمومی.
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            // اعتبارسنجی یونیک بودن slug با نادیده گرفتن پست فعلی
            'slug' => 'required|string|unique:posts,slug,' . $id, 
            'content' => 'required',
        ]);
        
        // به‌روزرسانی پست
        $post->update($request->only(['title', 'slug', 'content']));

        // ----------------------------------------------------
        // هدایت به صفحه اصلی عمومی (مطابق با درخواست شما)
        // ----------------------------------------------------
        return redirect()->route('posts.index')->with('success', 'پست با موفقیت به‌روزرسانی شد.');
    }

    /**
     * حذف پست.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        
        // هدایت به لیست پست‌های ادمین پس از حذف (رفتار استاندارد)
        return redirect()->route('admin.posts.index')->with('success', 'پست با موفقیت حذف شد.');
    }
}
