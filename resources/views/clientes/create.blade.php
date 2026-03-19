@extends('layouts.app')

@section('title', 'Novo Cliente')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-person-plus me-2"></i>Novo Cliente</h4>
    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('clientes.store') }}">
            @csrf
            @include('clientes.partials.form')
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-check-lg me-1"></i>Salvar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@include('clientes.partials.cep-script')
@endpush
