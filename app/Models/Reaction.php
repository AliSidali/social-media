<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    const UPDATED_AT = null;
    protected $fillable = ['user_id', 'reactionable_id', 'reactionable_type', 'type', 'image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reactionable()
    {
        return $this->morphTo();
    }




}
