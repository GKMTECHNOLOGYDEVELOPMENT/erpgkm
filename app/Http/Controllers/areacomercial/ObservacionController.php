<?php
namespace App\Http\Controllers\areacomercial;
use App\Http\Controllers\Controller;
use App\Models\Seguimiento;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\Request;

class ObservacionController extends Controller
{
  
public function index(Seguimiento $seguimiento)
{
    // Asegurarnos de que siempre devolvemos una colección, incluso si está vacía
    $tasks = Task::with(['status', 'user', 'empresa', 'contacto'])
        ->where('seguimiento_id', $seguimiento->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
    $statuses = Status::where('user_id', auth()->id())->get();

    return view('areacomercial.partials.observaciones-tab', [
        'tasks' => $tasks ?: collect(), // Asegura que siempre sea una colección
        'statuses' => $statuses,
        'seguimiento' => $seguimiento
    ]);
}

    public function store(Request $request, Seguimiento $seguimiento)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status_id' => 'required|exists:statuses,id'
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status_id' => $request->status_id,
            'user_id' => auth()->id(),
            'seguimiento_id' => $seguimiento->id,
            'empresa_id' => $seguimiento->idEmpresa,
            'contacto_id' => $seguimiento->idContacto
        ]);

        return redirect()->back()->with('success', 'Observación creada correctamente');
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status_id' => 'required|exists:statuses,id'
        ]);

        $task->update($request->only(['title', 'description', 'status_id']));

        return redirect()->back()->with('success', 'Observación actualizada correctamente');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Observación eliminada correctamente');
    }

}