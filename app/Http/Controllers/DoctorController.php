<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor ;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors= Doctor::all()  ;
        return $this->success($doctors ,"Doctors retrieved successfully" ) ;
    }
}
