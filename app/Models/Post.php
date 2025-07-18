<?php

namespace App\Models;

use App\Models\User;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['id', 'user_id', 'body', 'url_preview', 'group_id', 'isPinned'];

    protected $casts = [
        'url_preview' => 'json'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class)->select('id', 'name', 'slug', 'pinned_post_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }

    public function post_comments()
    {
        return $this->hasMany(PostComment::class)->latest();
    }

    public function latestLimitedComments()
    {
        return $this->hasMany(PostComment::class)->latest()->limit(5);
    }

    public function isOwner($userId)
    {
        return $this->user_id === $userId;
    }

    public function createdNotifications()
    {
        return $this->morphMany(Notification::class, 'notificable');
    }

    public function pin_posts()
    {
        return $this->hasMany(PinPost::class);
    }

}
