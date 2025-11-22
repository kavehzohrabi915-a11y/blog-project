<?php

// app/Models/Comment.php
namespace App\Models;
// ...
class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'user_id', 'content', 'author_name', 'author_email', 'is_approved'];
    
    // رابطه یک به چند معکوس: یک کامنت متعلق به یک پست است.
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // رابطه یک به چند معکوس: یک کامنت متعلق به یک کاربر است (اختیاری).
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}