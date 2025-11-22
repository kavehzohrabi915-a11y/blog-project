<?php

// app/Models/Post.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // ستون‌هایی که می‌توانند به صورت انبوه (Mass Assignable) پر شوند.
    protected $fillable = [
        'user_id', 'title', 'slug', 'content', 'thumbnail', 'published_at',
    ];

    // رابطه یک به چند معکوس: یک پست متعلق به یک کاربر (نویسنده) است.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // رابطه چند به چند: یک پست می‌تواند چندین برچسب داشته باشد.
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // رابطه یک به چند: یک پست می‌تواند چندین کامنت داشته باشد.
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    // اگر در جدول posts ستون category_id را اضافه کرده‌اید:
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
