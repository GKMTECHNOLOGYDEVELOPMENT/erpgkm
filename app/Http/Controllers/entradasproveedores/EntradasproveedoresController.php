<?php
namespace App\Http\Controllers\entradasproveedores;

use App\Http\Controllers\Controller;


class EntradasproveedoresController extends Controller 
{
    public function index (){


        return view('almacen.entradasproveedores.index');
    }
}