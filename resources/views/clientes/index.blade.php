@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-people me-2"></i>Clientes</h4>
    <a href="{{ route('clientes.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-lg me-1"></i>Novo Cliente
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        @if($clientes->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                Nenhum cliente cadastrado ainda.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Cidade/UF</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->nome }}</td>
                            <td>{{ $cliente->email }}</td>
                            <td>{{ $cliente->telefone }}</td>
                            <td>{{ $cliente->cidade }}/{{ $cliente->estado }}</td>
                            <td class="text-center">
                                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('clientes.destroy', $cliente) }}" class="d-inline"
                                      onsubmit="return confirm('Confirma a exclusão de {{ addslashes($cliente->nome) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-3 py-2">
                {{ $clientes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
