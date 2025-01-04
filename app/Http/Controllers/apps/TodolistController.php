<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;

class TodolistController extends Controller
{
    public function index()
    {
        return view('apps.todolist'); // Vista para todolist
    }
}
