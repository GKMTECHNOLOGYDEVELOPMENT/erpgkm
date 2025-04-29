<?php

namespace App\Http\Controllers\politicas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoliticasController extends Controller
{
    public function index()
    {
        return view('politicas.index');
    }
}
