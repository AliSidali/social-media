<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PinPost extends Model
{
    protected $fillable = ['post_id', 'pinable_id', 'pinable_type'];

    public function pinable()
    {
        return $this->morphTo();
    }
}
