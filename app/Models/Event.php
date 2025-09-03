<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'description',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'is_public',
        'notification_email',
        'address',
        'is_free',
        'price',
        'max_participants',
        'latitude',
        'longitude',
        'images',
        'document',
        'user_id',
        'status',
        'status_reason','is_fetured'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_free' => 'boolean',
        'images' => 'array',
    ];
  
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function participants()
    {
        return $this->hasMany(EventParticipation::class);
    }
    public function ratings()
    {
        return $this->hasMany(EventRating::class);
    }
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating');
    }
}