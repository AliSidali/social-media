<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;

    public $childComments = [];
    public $numOfComments = null;

    protected $fillable = ['user_id', 'post_id', 'text', 'parent_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }

    public function parent()
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id')->latest();
    }

    public function attachment()
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($comment) {
            self::delete_attachments($comment->replies);
            $comment->attachment()->delete();
        });
    }

    private static function delete_attachments($replies)
    {
        foreach ($replies as $reply) {
            $reply->attachment()->delete();
            self::delete_attachments($reply->replies);
        }
    }

}
