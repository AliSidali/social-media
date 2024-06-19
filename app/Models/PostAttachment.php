<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostAttachment extends Model
{
    use HasFactory;

    const UPDATED_AT = null;
    protected $fillable = ['post_id', 'name', 'path', 'mime', 'size', 'created_by'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
