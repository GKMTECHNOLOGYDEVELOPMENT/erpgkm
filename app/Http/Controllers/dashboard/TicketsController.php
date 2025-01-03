<?php

namespace App\Http\Controllers\dashboard; 
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TicketsController extends Controller
{
    public function index()
    {
        return view('tickets'); // Vista de tickets
    }
}
