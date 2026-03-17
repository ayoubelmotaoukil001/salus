<?php

namespace App\Models;
use App\Models\User ;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    protected $fillable = ['user_id', 'name', 'severity', 'description', 'date_recorded', 'notes'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
