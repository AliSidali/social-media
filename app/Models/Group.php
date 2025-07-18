<?php

namespace App\Models;

use App\Http\Enums\StatusEnum;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'cover_path', 'thumbnail_path', 'auto_approval', 'about', 'user_id', 'pinned_post_id'];
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('status', 'role', 'token', 'token_expire_date', 'token_used');
    }


    public function isCurrentUserAdmin()
    {
        return $this->users()->where(['user_id' => auth()->user()->id, 'role' => 'admin'])->exists();

    }

    public function isCurrentUserApproved()
    {
        return $this->users()->where(['user_id' => auth()->user()->id, 'status' => StatusEnum::APPROVED->value])->exists();
    }

    public function getPendingUsers()
    {
        return $this->belongsToMany(User::class, 'group_user')
            ->withPivot('status', 'role')
            ->wherePivot('status', StatusEnum::PENDING->value);
    }

    public function getApprovedUsers()
    {
        return $this->belongsToMany(User::class, 'group_user')
            ->withPivot('status', 'role')
            ->wherePivot('status', StatusEnum::APPROVED->value);
    }

    public function createdNotifications()
    {
        return $this->morphMany(Notification::class, 'notificable');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function isAdmin($user_id)
    {
        return $this->users()->wherePivot('role', 'admin')->wherePivot('user_id', $user_id)->exists();
    }

    public function pin_post()
    {
        return $this->morphOne(PinPost::class, 'pinable');
    }
}
