<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;

class CalendarController extends Controller
{
    public function index()
    {
        return view('apps.calendar'); // Vista para calendar
    }
}
