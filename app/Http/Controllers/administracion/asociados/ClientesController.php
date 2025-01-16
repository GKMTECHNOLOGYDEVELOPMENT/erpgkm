<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Models\Clientegeneral;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientesController extends Controller
{
    public function index()
    {
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);

        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.clientes.index', compact('departamentos')); 
    }
    

    public function store(ClienteRequest $request)
    {
        $dataClientes = $request->except('_token');
        $dataClientes = [
            'id_cliente_general' => $request->id_cliente_general,
            'fecha_registro' => Carbon::now(),
            'nombre' => $request->nombre,
            'documento' => $request->documento,
            'direccion' => $request->direccion,
            'departamento' => $request->departamento,
            'provincia' => $request->provincia,
            'distrito' => $request->distrito,
            'pais' => $request->pais,
            'telefono' => $request->telefono,
            'celular' => $request->celular,
            'email' => $request->email,
            'cod_postal' => $request->cod_postal,
        ];


        Cliente::insert($dataClientes);
        $lastId = Cliente::latest('id')->first();
        $id = $lastId->id;

        if (isset($request->emailContact) || isset($request->telContact)) {
            if (isset($request->emailContact)) {
                $emailContact  = $request->emailContact;
            } else {
                $emailContact = '';
            }

            if (isset($request->telContact)) {
                $telContact  = $request->telContact;
            } else {
                $telContact  = '';
            }

            foreach ($emailContact as $key => $value) {
                $dataCustomer = [
                    'id_cliente' => $id,
                    'email' => $value,
                    'celular' => $telContact[$key]
                ];
                contacto_cliente::insert($dataCustomer);
            }
        }

        return redirect('clientes')->with('addClientes', 'ok');
    }

    public function edit($id)
    {
        $dataClientes = Cliente::findOrFail($id);
        $contacts = DB::table('contacto_clientes')
            ->select('id', 'id_cliente', 'email', 'celular')
            ->where('id_cliente', '=', $dataClientes->id)
            ->get();
        $generales = Clientegeneral::all()->where('estado', '!=', 2)->where('estado', '!=', 0);
        return view('clientes.edit', compact('dataClientes', 'contacts', 'generales'));
    }

    public function update(Request $request, $id)
    {
        $dataClientes = request()->except(['_token', '_method', 'telContact', 'emailContact']);

        Cliente::where('id', '=', $id)->update($dataClientes);


        contacto_cliente::where('id_cliente', '=', $id)->delete();


        if (isset($request->emailContact) || isset($request->telContact)) {
            if (isset($request->emailContact)) {
                $emailContact  = $request->emailContact;
            } else {
                $emailContact = '';
            }

            if (isset($request->telContact)) {
                $telContact  = $request->telContact;
            } else {
                $telContact  = '';
            }

            foreach ($emailContact as $key => $value) {
                $dataCustomer = [
                    'id_cliente' => $id,
                    'email' => $value,
                    'celular' => $telContact[$key]
                ];
                contacto_cliente::insert($dataCustomer);
            }
        }

        $dataClientes = Cliente::findOrFail($id);
        return redirect('clientes')->with('updateCliente', 'ok');


        // return redirect('clientes', compact('arrayContact', 'arrayContact'))->with('updateCliente', 'ok');
        // return view('clientes.index', compact('arrayContact', 'arrayContact'));
    }

    public function getAll()
    {
        // Obtener todos los clientes con sus relaciones (TipoDocumento y ClienteGeneral)
        $clientes = Cliente::with(['tipoDocumento', 'clienteGeneral'])->get();
    
        // Procesa los datos para incluir los campos necesarios, mostrando los nombres relacionados
        $clientesData = $clientes->map(function ($cliente) {
            return [
                'idTipoDocumento' => $cliente->tipoDocumento->nombre, // Mostrar nombre del tipo de documento
                'documento'       => $cliente->documento,
                'nombre'          => $cliente->nombre,
                'telefono'        => $cliente->telefono,
                'email'           => $cliente->email,
                'clienteGeneral'  => $cliente->clienteGeneral->descripcion, // Mostrar descripción de cliente general
                'direccion'       => $cliente->direccion,
                'estado'          => $cliente->estado == 1 ? 'Activo' : 'Inactivo',
            ];
        });
    
        // Retorna los datos en formato JSON
        return response()->json($clientesData);
    }
    


    public function deleteCliente(Request $request)
    {
        Cliente::where('id', '=', $request->idCliente)->update(['estado' => 1]);
        return true;
    }

}