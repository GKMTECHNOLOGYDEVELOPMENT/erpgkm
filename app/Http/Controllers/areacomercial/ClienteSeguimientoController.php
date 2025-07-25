<?php
namespace App\Http\Controllers\areacomercial;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Tipoarea;
use App\Models\Tipodocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClienteSeguimientoController extends Controller
{
   public function index()
{
    $clientes = Cliente::with(['servicio', 'tipoDocumento'])
        ->orderBy('idCliente', 'desc')
        ->paginate(10);
    
    return view('areacomercial.index', compact('clientes'));
}

public function tabsseguimiento(){

    return view('areacomercial.tabsseguimiento');
}



    public function show(string $id)
    {
        $cliente = Cliente::with(['servicio', 'tipoDocumento'])->find($id);

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $cliente
        ]);
    }


    public function create()
    {
           $servicios = Servicio::all();
        // Asume que tienes un modelo TipoDocumento
        $tipoDocumentos = Tipodocumento::all();
        // Aquí puedes implementar la lógica para crear un nuevo seguimiento de cliente
        return view('areacomercial.create', compact('servicios', 'tipoDocumentos'));
    }

   /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|max:255|unique:cliente,documento',
            'telefono' => 'required|string|max:255|unique:cliente,telefono',
            'email' => 'required|email|max:255|unique:cliente,email',
            'idTipoDocumento' => 'required|exists:tipodocumento,idTipoDocumento',
            'idservicio' => 'required|exists:servicios,idServicios',
          
        ]);

        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'documento' => $request->documento,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'fecha_registro' => now(),
            'idTipoDocumento' => $request->idTipoDocumento,
            'idservicio' => $request->idservicio,
            'estado' => 1, // Asumiendo que 1 es activo
            'idtipoarea' => 11,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cliente registrado correctamente',
            'cliente' => $cliente
        ]);
    }


    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        $servicios = Servicio::all();
        $tipoDocumentos = Tipodocumento::all();
        
        return view('areacomercial.edit', compact('cliente', 'servicios', 'tipoDocumentos'));
    }

public function update(Request $request, $id)
{
    $cliente = Cliente::findOrFail($id);
    
    $request->validate([
        'nombre' => 'required|string|max:255',
        'documento' => [
            'required',
            'string',
            'max:255',
            Rule::unique('cliente', 'documento')->ignore($cliente->idCliente, 'idCliente')
        ],
        'telefono' => [
            'required',
            'string',
            'max:255',
            Rule::unique('cliente', 'telefono')->ignore($cliente->idCliente, 'idCliente')
        ],
        'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique('cliente', 'email')->ignore($cliente->idCliente, 'idCliente')
        ],
        'idTipoDocumento' => 'required|exists:tipodocumento,idTipoDocumento',
        'idservicio' => 'required|exists:servicios,idServicios',
    ]);

    $cliente->update([
        'nombre' => $request->nombre,
        'documento' => $request->documento,
        'telefono' => $request->telefono,
        'email' => $request->email,
        'idTipoDocumento' => $request->idTipoDocumento,
        'idservicio' => $request->idservicio,
        // Puedes actualizar otros campos según sea necesario
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Cliente actualizado correctamente',
        'cliente' => $cliente
    ]);
}
  public function destroy(string $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        $cliente->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado correctamente'
        ]);
    }
}