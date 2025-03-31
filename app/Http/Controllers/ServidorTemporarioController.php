<?php

namespace App\Http\Controllers;

use App\Http\Requests\servidor_temporario\ServidorTemporarioListRequest;
use App\Http\Requests\servidor_temporario\ServidorTemporarioStoreRequest;
use App\Services\servidor_temporario\ServidorTemporarioDestroyService;
use App\Services\servidor_temporario\ServidorTemporarioFindService;
use App\Services\servidor_temporario\ServidorTemporarioListService;
use App\Services\servidor_temporario\ServidorTemporarioStoreService;
use App\Services\servidor_temporario\ServidorTemporarioUpdateService;

class ServidorTemporarioController extends Controller
{

    public function __construct(
        private ServidorTemporarioListService $servidorTemporarioListService,
        private ServidorTemporarioStoreService $servidorTemporarioStoreService,
        private ServidorTemporarioFindService $servidorTemporarioFindService,
        private ServidorTemporarioDestroyService $servidorTemporarioDestroyService,
        private ServidorTemporarioUpdateService $servidorTemporarioUpdateService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(ServidorTemporarioListRequest $request)
    {
        $perPage = $request->input('per_page', 15); // Se não for informado, o padrão será 15

        if ($servidores = $this->servidorTemporarioListService->handle($perPage)) {
            return response()->json([
                'status' => 'success',
                'data' => $servidores,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Erro ao listar os servidores temporários. Verifique os parâmetros ou tente novamente mais tarde.',
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
    public function store(ServidorTemporarioStoreRequest $request)
    {
        if ($servidor = $this->servidorTemporarioStoreService->handle($request->all())) {
            return response()->json([
                'status' => 'success',
                'data' => $servidor,
                'message' => 'Servidor temporário criado com sucesso'
            ], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Erro ao criar servidor temporário. Verifique os dados enviados.'
        ], 422);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        if ($servidor = $this->servidorTemporarioFindService->handle($id)) {
            return response()->json([
                'status' => 'success',
                'data' => $servidor,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Servidor temporário não encontrado. Verifique o ID fornecido.',
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
    public function update(ServidorTemporarioStoreRequest $request, string $id)
    {
        if ($servidor = $this->servidorTemporarioUpdateService->handle($id, $request->all())) {
            return response()->json([
                'status' => 'success',
                'message' => 'Servidor temporário atualizado com sucesso.',
                'data' => $servidor,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Servidor temporário não encontrado ou erro ao atualizar.',
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $servidor = $this->servidorTemporarioDestroyService->handle($id);

        if ($servidor) {
            return response()->json([
                'status' => 'success',
                'message' => 'Servidor temporário deletado com sucesso.',
                'data' => $servidor,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Servidor temporário não encontrado ou erro ao deletar.',
        ], 404);
    }
}
