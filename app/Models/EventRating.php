<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRating extends Model
{
    //
    protected $fillable = [
        'user_id',
        'event_id',
        'rating'
    ];
}
