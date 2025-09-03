<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipation extends Model
{
    //
    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'status_reason',
        'participant_names',
        'participant_emails',
        'number_of_participants'
    ];
    protected $casts = [
        'status' => 'string',
        'status_reason' => 'string',
        'participant_names' => 'array',
        'participant_emails' => 'array',
        'number_of_participants' => 'integer',
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
