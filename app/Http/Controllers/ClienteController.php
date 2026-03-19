<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Services\CepService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function __construct(private readonly CepService $cepService)
    {
    }

    public function index(): View
    {
        $clientes = Cliente::orderBy('nome')->paginate(10);

        return view('clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        return view('clientes.create');
    }

    public function store(ClienteRequest $request): RedirectResponse
    {
        Cliente::create($request->validated());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente cadastrado com sucesso.');
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(ClienteRequest $request, Cliente $cliente): RedirectResponse
    {
        $cliente->update($request->validated());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente excluído com sucesso.');
    }

    /**
     * Endpoint AJAX para consulta de CEP via API dos Correios.
     */
    public function consultarCep(string $cep)
    {
        try {
            $dados = $this->cepService->consultarCep($cep);
            return response()->json($dados);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
