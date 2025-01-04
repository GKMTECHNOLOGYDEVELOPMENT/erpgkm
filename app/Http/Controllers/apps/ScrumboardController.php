<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;

class ScrumboardController extends Controller
{
    public function index()
    {
        return view('apps.scrumboard'); // Vista para scrumboard
    }
}
