<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventChat extends Model
{
    //
    protected $fillable = [
        'user_id',
        'event_id',
        'message'
    ];
    protected $casts = [
        'message' => 'string',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
