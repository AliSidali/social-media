<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;

    const UPDATED_AT = null;
    protected $fillable = ['attachable_id', 'attachable_type', 'name', 'path', 'mime', 'size', 'created_by'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleted(function (self $attachment) {
            Storage::disk('public')->delete($attachment->path);
        });
    }

    public function attachable()
    {
        return $this->morphTo();

    }
}
