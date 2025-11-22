<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category; // مدل Category را ایمپورت می‌کنیم
use App\Models\Tag;      // مدل Tag را ایمپورت می‌کنیم
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // برای مدیریت فایل‌ها
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * نمایش لیست پست‌های منتشر شده در صفحه اصلی با صفحه‌بندی.
     */
   public function index()
{
    $posts = Post::whereNotNull('published_at')
                 ->with('user', 'category')
                 ->latest('published_at')
                 ->paginate(10);

    return view('posts.index', compact('posts'));
}
    
    /**
     * نمایش فرم برای ساخت یک پست جدید (برای کاربران عادی).
     */
    public function create()
    {
        // در اینجا می‌توانید مدل‌ها و دسته‌بندی‌ها را برای استفاده در فرم ارسال کنید
        // فرض می‌کنیم که Category و Tag از قبل مدل شده‌اند.
        $categories = Category::all(); 
        $tags = Tag::all();
        return view('posts.create', compact('categories', 'tags'));
    }

    /**
     * ذخیره پست جدید در دیتابیس.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'tag_ids' => 'nullable|array',
        'tag_ids.*' => 'exists:tags,id',
        'category_id' => 'required|exists:categories,id',
    ]);

    // تولید slug یکتا
    $slug = Str::slug($validated['title']);
    $originalSlug = $slug;
    $count = 1;
    while (Post::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $count++;
    }

    $thumbnailPath = null;
    if ($request->hasFile('thumbnail')) {
        $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
    }

    $post = Post::create([
        'user_id' => Auth::id(),
        'title' => $validated['title'],
        'slug' => $slug,
        'content' => $validated['content'],
        'thumbnail' => $thumbnailPath,
        'category_id' => $validated['category_id'],
        'published_at' => $request->has('publish') ? now() : null,
    ]);

    if (!empty($validated['tag_ids'])) {
        $post->tags()->attach($validated['tag_ids']);
    }

    return redirect()->route('dashboard')->with('success', 'پست با موفقیت ذخیره شد.');
}
    
    /**
     * نمایش جزئیات یک پست خاص بر اساس slug آن.
     */
    public function show(Post $post)
    {
        // بررسی می‌کند که پست حتماً منتشر شده باشد
        // اگر منتشر نشده باشد، فقط نویسنده پست اجازه مشاهده دارد.
        if (is_null($post->published_at)) {
            // اگر لاگین نکرده یا نویسنده پست نیست، 404
            if (!Auth::check() || $post->user_id !== Auth::id()) {
                abort(404);
            }
        }
        
        // واکشی نظرات تأیید شده و کاربر نویسنده
        $post->load(['user', 'comments' => function ($query) {
            $query->where('is_approved', true)->latest();
        }]);

        return view('posts.show', compact('post'));
    }

    /**
     * نمایش فرم ویرایش پست (فقط برای نویسنده).
     */
    public function edit(Post $post)
    {
        // بررسی مجوزها: فقط نویسنده پست اجازه ویرایش دارد
        if ($post->user_id !== Auth::id()) {
            abort(403, 'شما اجازه دسترسی به این محتوا را ندارید.');
        }

        $categories = Category::all(); 
        $tags = Tag::all();
        
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * به‌روزرسانی پست در دیتابیس (فقط توسط نویسنده).
     */
public function update(Request $request, Post $post)
{
    if ($post->user_id !== Auth::id()) {
        abort(403);
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'tag_ids' => 'nullable|array',
        'tag_ids.*' => 'exists:tags,id',
        'category_id' => 'required|exists:categories,id',
    ]);

    // slug یکتا (به جز پست فعلی)
    $slug = Str::slug($validated['title']);
    $originalSlug = $slug;
    $count = 1;
    while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
        $slug = $originalSlug . '-' . $count++;
    }

    $thumbnailPath = $post->thumbnail;
    if ($request->hasFile('thumbnail')) {
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }
        $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
    }

    $publishedAt = $post->published_at;
    if ($request->has('unpublish')) {
        $publishedAt = null;
    } elseif ($request->has('publish')) {
        $publishedAt = now();
    }

    $post->update([
        'title' => $validated['title'],
        'slug' => $slug,
        'content' => $validated['content'],
        'thumbnail' => $thumbnailPath,
        'category_id' => $validated['category_id'],
        'published_at' => $publishedAt,
    ]);

    $post->tags()->sync($validated['tag_ids'] ?? []);

    return redirect()->route('dashboard')->with('success', 'پست با موفقیت به‌روز شد.');
}
    /**
     * حذف پست (فقط توسط نویسنده).
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
        
        // حذف فایل تصویر مرتبط (Thumbnail)
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        // حذف رکورد
        $post->delete(); 
        
        return redirect()->route('dashboard')->with('success', 'پست با موفقیت حذف شد.');
    }
}
