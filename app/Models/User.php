<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Post;
use App\Traits\ModelsMethods;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    use HasSlug;
    use ModelsMethods;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'cover_path',
        'avatar_path',
        'pinned_post_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];



    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('username')
            ->usingSeparator('.');
        ;
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }

    public function post_comments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class)->withPivot('status', 'role');

    }

    public function receivedNotifications()//THE USER WHO OWN THE NOTIFICATIONS, BELONG TO HIM
    {
        return $this->hasMany(Notification::class)->with('notificable')->limit(20)->latest();
    }

    public function createdNotifications()//THE USER WHO CREATES THE NOTIFICATION AND SAVE IT FOR RECEIVER
    {
        return $this->morphMany(Notification::class, 'notificable');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follower_users', 'followed_id', 'follower_id');
    }

    public function followedUsers()
    {
        return $this->belongsToMany(User::class, 'follower_users', 'follower_id', 'followed_id');
    }

    public function pin_post()
    {
        return $this->morphOne(PinPost::class, 'pinable');
    }


    //
}
