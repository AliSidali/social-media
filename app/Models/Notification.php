<?php

namespace App\Models;

use App\Traits\ModelsMethods;
use Illuminate\Database\Eloquent\Model;
class Notification extends Model
{
    use ModelsMethods;
    protected $fillable = ['user_id', 'title', 'message', 'is_read', 'notificable_id', 'notificable_type'];


    public function notificable()
    {
        return $this->morphTo();
    }





}
