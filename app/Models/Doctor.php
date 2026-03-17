<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['name' , 'specialty' , 'city' , 'years_of_experience'  , 'consultation_price'  , 'available_days'];
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    
}
