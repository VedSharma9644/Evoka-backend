<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'subscription_type',
        'price',
    ];
}
