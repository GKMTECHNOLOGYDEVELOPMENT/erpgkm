<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function index()
    {
        return view('apps.chat'); // Vista para chat
    }
}
