@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Cliente</h4>
    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('clientes.update', $cliente) }}">
            @csrf
            @method('PUT')
            @include('clientes.partials.form')
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-check-lg me-1"></i>Atualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@include('clientes.partials.cep-script')
@endpush
