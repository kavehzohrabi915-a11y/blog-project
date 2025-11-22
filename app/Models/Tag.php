<?php

// app/Models/Tag.php
namespace App\Models;
// ...
class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];
    
    // رابطه چند به چند معکوس: یک برچسب به چندین پست متصل است.
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}