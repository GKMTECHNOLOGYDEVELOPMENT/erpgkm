<?php

namespace App\Http\Controllers\dashboard; 
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AdministracionController extends Controller
{
    public function index()
    {
        return view('index'); // Vista de administración
    }
}
