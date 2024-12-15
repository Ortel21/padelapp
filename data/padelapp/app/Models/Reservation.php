<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'court_number',
        'start_time',
        'duration_minutes',
        'status',
    ];

    public $timestamps = false;

    protected $casts = [
        'start_time' => 'datetime', // convierte carbon recordar
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
