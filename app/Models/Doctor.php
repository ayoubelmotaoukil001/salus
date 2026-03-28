<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'name',
        'specialty',
        'city',
        'yearsofexperience',
        'consultation_price',
        'available_days'
    ];

    protected $casts = [
        'available_days' => 'array'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
