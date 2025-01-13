<?php
namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Models\Subsidiario;
use Illuminate\Http\Request;

class SubsidiarioController extends Controller
{
    public function index()
    {
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.subsidiario.index'); 
    }

    public function create()
    {
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.subsidiario.create'); 
    }
    // Crear un nuevo Subsidiario
    //  public function store(Request $request)
    //  {
    //      $request->validate([
    //          'ruc' => 'required|string|max:255',
    //          'nombre' => 'required|string|max:255',
    //          'nombre_contacto' => 'required|string|max:255',
    //          'celular' => 'required|string|max:255',
    //          'email' => 'required|string|email|max:255',
    //          'direccion' => 'required|string|max:255',
    //          'referencia' => 'required|string|max:255',
    //          'idTienda' => 'required|integer',
    //      ]);
 
    //      $subsidiario = new Subsidiario();
    //      $subsidiario->ruc = $request->ruc;
    //      $subsidiario->nombre = $request->nombre;
    //      $subsidiario->nombre_contacto = $request->nombre_contacto;
    //      $subsidiario->celular = $request->celular;
    //      $subsidiario->email = $request->email;
    //      $subsidiario->direccion = $request->direccion;
    //      $subsidiario->referencia = $request->referencia;
    //      $subsidiario->idTienda = $request->idTienda;
    //      $subsidiario->save();
 
    //      return response()->json(['message' => 'Subsidiario creado correctamente'], 201);
    //  }

      // Actualizar un Subsidiario
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'ruc' => 'required|string|max:255',
    //         'nombre' => 'required|string|max:255',
    //         'nombre_contacto' => 'required|string|max:255',
    //         'celular' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255',
    //         'direccion' => 'required|string|max:255',
    //         'referencia' => 'required|string|max:255',
    //         'idTienda' => 'required|integer',
    //     ]);

    //     $subsidiario = Subsidiario::findOrFail($id);
    //     $subsidiario->ruc = $request->ruc;
    //     $subsidiario->nombre = $request->nombre;
    //     $subsidiario->nombre_contacto = $request->nombre_contacto;
    //     $subsidiario->celular = $request->celular;
    //     $subsidiario->email = $request->email;
    //     $subsidiario->direccion = $request->direccion;
    //     $subsidiario->referencia = $request->referencia;
    //     $subsidiario->idTienda = $request->idTienda;
    //     $subsidiario->save();

    //     return response()->json(['message' => 'Subsidiario actualizado correctamente']);
    // }

     // Eliminar un Subsidiario
    //  public function destroy($id)
    //  {
    //      $subsidiario = Subsidiario::findOrFail($id);
    //      $subsidiario->delete();
 
    //      return response()->json(['message' => 'Subsidiario eliminado correctamente']);
    //  }

     public function getAll()
{
    // Obtén todos los datos de la tabla subsidiarios
    $subsidiarios = Subsidiario::all();

    // Procesa los datos
    $subsidiariosData = $subsidiarios->map(function ($subsidiario) {
        return [
            'idSubsidiarios' => $subsidiario->idSubsidiarios,
            'ruc' => $subsidiario->ruc,
            'nombre' => $subsidiario->nombre,
            'nombre_contacto' => $subsidiario->nombre_contacto,
            'celular' => $subsidiario->celular,
            'email' => $subsidiario->email,
            'direccion' => $subsidiario->direccion,
            'referencia' => $subsidiario->referencia,
            'idTienda' => $subsidiario->idTienda,
        ];
    });

    // Retorna los datos en formato JSON
    return response()->json($subsidiariosData);
}

}