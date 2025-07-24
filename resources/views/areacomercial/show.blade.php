@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Detalles del Cliente</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $cliente->idCliente }}</p>
                    <p><strong>Nombre:</strong> {{ $cliente->nombre }}</p>
                    <p><strong>Tipo Documento:</strong> {{ $cliente->tipoDocumento->nombre ?? 'N/A' }}</p>
                    <p><strong>Documento:</strong> {{ $cliente->documento }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Teléfono:</strong> {{ $cliente->telefono }}</p>
                    <p><strong>Email:</strong> {{ $cliente->email }}</p>
                    <p><strong>Servicio:</strong> {{ $cliente->servicio->nombre ?? 'N/A' }}</p>
                    <p><strong>Fecha Registro:</strong> {{ $cliente->fecha_registro }}</p>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('areacomercial.clientes.edit', $cliente->idCliente) }}" 
                   class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <form action="{{ route('areacomercial.clientes.destroy', $cliente->idCliente) }}" 
                      method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                            onclick="return confirm('¿Estás seguro de eliminar este cliente?')">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </form>
                <a href="{{ route('areacomercial.clientes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
@endsection