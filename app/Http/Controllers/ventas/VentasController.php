<?php

namespace App\Http\Controllers\ventas;

use App\Http\Controllers\Controller;



class VentasController extends Controller
{

public function index()
    {
        return view('ventas.index');
    }

    public function create()
    {
        return view('ventas.create');
    }
    public function show($id)
    {
        return view('ventas.show', compact('id'));
    }

    public function edit($id)
    {
        return view('ventas.edit', compact('id'));
    }

    public function report()
    {
        return view('ventas.report');
    }

    public function invoice($id)
    {
        return view('ventas.invoice', compact('id'));
    }
    



}
