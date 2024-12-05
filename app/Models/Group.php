<?php

namespace App\Models;

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

    protected $fillable = ['name', 'slug', 'cover_path', 'thumbnail_path', 'auto_approval', 'about', 'user_id'];
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
}
