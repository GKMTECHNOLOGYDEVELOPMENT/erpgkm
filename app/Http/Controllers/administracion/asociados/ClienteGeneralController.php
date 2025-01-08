<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Models\Clientegeneral;
use App\Http\Requests\GeneralRequests;
use Illuminate\Http\Request;




class ClienteGeneralController extends Controller
{
    public function index()
    {
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.cliente-general'); 
    }
    public function store(GeneralRequests $request)
    {
        $dataClientes = [
            'descripcion' => $request->descripcion,
            // 'logo' => $request->logo,
            'estado' => 1,
        ];

        Clientegeneral::insert($dataClientes);
        $data = Clientegeneral::latest('id')->first();
        $idCliente = $data->id;

        if ($request->hasFile('logo')) {
            $extension = $request->file('logo')->getClientOriginalExtension();
            $file_name = mt_rand(0, 999) . '.' . $extension;

            $directorio = "img/general/" . $idCliente;
            if (!file_exists($directorio)) {
                mkdir($directorio, 0755);
            }

            $path =   $request->logo->move(public_path('img/general/' . $idCliente . '/'), $file_name);
            $rutaImg = "img/general/" . $idCliente . '/' . $file_name;
        } else {
            $rutaImg = $request->logoDefault;
        }

        $update = Clientegeneral::where('id', '=', $idCliente)->update(['foto' => $rutaImg]);



        return redirect('cliente-general')->with('addClientes', 'ok');
    }
    
    

    public function edit($id)
    {
        $dataClientes = Clientegeneral::findOrFail($id);
        return view('cliente-general.edit', compact('dataClientes'));
    }

    public function update(GeneralRequests $request, $id)
    {
        // return $request->all();
        if ($request->hasFile('logo')) {
            $extension = $request->file('logo')->getClientOriginalExtension();
            $file_name = mt_rand(0, 999) . '.' . $extension;

            $directorio = "img/general/" . $id;
            if (!file_exists($directorio)) {
                mkdir($directorio, 0755);
            }

            $path =   $request->logo->move(public_path('img/general/' . $id . '/'), $file_name);
            $rutaImg = "img/general/" . $id . '/' . $file_name;
        } else {
            $rutaImg = $request->logoDefault;
        }

        $update = Clientegeneral::where('id', '=', $id)->update([
            'descripcion' => $request->descripcion,
            'foto' => $rutaImg,
            'estado' => $request->estado
        ]);


        return redirect('cliente-general')->with('updateClientes', 'ok');
    }


    public function deleteClienteGeneral(Request $request)
    {
        $update = Clientegeneral::where('id', '=', $request->idGeneral)->update([
            'estado' => 2,
        ]);
        if ($update > 0) {
            return response()->json(['success' => $request->idGeneral]);
        }
    }



    public function getAll()
    {
        // Obtén todos los datos de la tabla clientegeneral
        $clientes = Clientegeneral::all();
    
        // Procesa los datos sin la columna 'foto'
        $clientesData = $clientes->map(function ($cliente) {
            return [
                'idClienteGeneral' => $cliente->idClienteGeneral,
                'descripcion' => $cliente->descripcion,
                'estado' => $cliente->estado ? 'Activo' : 'Inactivo', // Convertir estado a texto
            ];
        });
    
        // Retorna los datos en formato JSON
        return response()->json($clientesData);
    }
    



}
