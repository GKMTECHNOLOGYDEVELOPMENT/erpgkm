<?php

namespace App\Http\Controllers\dashboard; 
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    public function index()
    {
        return view('almacen'); // Vista de almacén
    }
}
