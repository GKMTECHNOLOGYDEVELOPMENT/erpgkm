<?php

namespace App\Http\Controllers\dashboard; 
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ComercialController extends Controller
{
    public function index()
    {
        return view('comercial'); // Vista de comercial
    }
}
