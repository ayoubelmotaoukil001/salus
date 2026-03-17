<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthAdvice extends Model
{
    protected $fillable = ['user_id', 'advice', 'symptoms_used', 'generated_at'];
    public function user()
    {
       return $this->belongsTo(User::class);
    }
}
