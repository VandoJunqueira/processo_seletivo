<?php

namespace App\Http\Controllers;

use App\Http\Requests\servidor_efetivo\ServidorEfetivoListRequest;
use App\Http\Requests\servidor_efetivo\ServidorEfetivoStoreRequest;
use App\Services\servidor_efetivo\ServidorEfetivoDestroyService;
use App\Services\servidor_efetivo\ServidorEfetivoFindService;
use App\Services\servidor_efetivo\ServidorEfetivoListService;
use App\Services\servidor_efetivo\ServidorEfetivoStoreService;
use App\Services\servidor_efetivo\ServidorEfetivoUpdateService;

class ServidorEfetivoController extends Controller
{

    public function __construct(
        private ServidorEfetivoListService $servidorEfetivoListService,
        private ServidorEfetivoStoreService $servidorEfetivoStoreService,
        private ServidorEfetivoFindService $servidorEfetivoFindService,
        private ServidorEfetivoDestroyService $servidorEfetivoDestroyService,
        private ServidorEfetivoUpdateService $servidorEfetivoUpdateService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(ServidorEfetivoListRequest $request)
    {
        $perPage = $request->input('per_page', 15); // Se não for informado, o padrão será 15

        if ($servidores = $this->servidorEfetivoListService->handle($perPage)) {
            return response()->json([
                'status' => 'success',
                'data' => $servidores,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Erro ao listar os servidores efetivos. Verifique os parâmetros ou tente novamente mais tarde.',
        ], 422);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Resource not found'], 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServidorEfetivoStoreRequest $request)
    {
        if ($servidor = $this->servidorEfetivoStoreService->handle($request->all())) {
            return response()->json([
                'status' => 'success',
                'data' => $servidor,
                'message' => 'Servidor efetivo criado com sucesso'
            ], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Erro ao criar servidor efetivo. Verifique os dados enviados.'
        ], 422);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        if ($servidor = $this->servidorEfetivoFindService->handle($id)) {
            return response()->json([
                'status' => 'success',
                'data' => $servidor,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Servidor efetivo não encontrado. Verifique o ID fornecido.',
        ], 404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'This method is not allowed for this resource.'
        ], 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServidorEfetivoStoreRequest $request, string $id)
    {
        if ($servidor = $this->servidorEfetivoUpdateService->handle($id, $request->all())) {
            return response()->json([
                'status' => 'success',
                'message' => 'Servidor efetivo atualizado com sucesso.',
                'data' => $servidor,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Servidor efetivo não encontrado ou erro ao atualizar.',
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $servidor = $this->servidorEfetivoDestroyService->handle($id);

        if ($servidor) {
            return response()->json([
                'status' => 'success',
                'message' => 'Servidor efetivo deletado com sucesso.',
                'data' => $servidor,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Servidor efetivo não encontrado ou erro ao deletar.',
        ], 404);
    }
}
