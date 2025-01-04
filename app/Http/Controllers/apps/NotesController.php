<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;

class NotesController extends Controller
{
    public function index()
    {
        return view('apps.notes'); // Vista para notes
    }
}
