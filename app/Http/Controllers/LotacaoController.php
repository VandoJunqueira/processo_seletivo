<?php

namespace App\Http\Controllers;

use App\Http\Requests\lotacao\LotacaoListRequest;
use App\Http\Requests\lotacao\LotacaoStoreRequest;
use App\Services\Lotacao\LotacaoDestroyService;
use App\Services\lotacao\LotacaoFindService;
use App\Services\Lotacao\LotacaoListService;
use App\Services\lotacao\LotacaoStoreService;
use App\Services\Lotacao\LotacaoUpdateService;
use Illuminate\Http\Request;

class LotacaoController extends Controller
{

    public function __construct(
        private LotacaoListService $lotacaoListService,
        private LotacaoStoreService $lotacaoStoreService,
        private LotacaoFindService $lotacaoFindService,
        private LotacaoDestroyService $lotacaoDestroyService,
        private LotacaoUpdateService $lotacaoUpdateService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(LotacaoListRequest $request)
    {
        $perPage = $request->input('per_page', 15); // Se não for informado, o padrão será 15

        if ($lotacoes = $this->lotacaoListService->handle($perPage)) {
            return response()->json([
                'status' => 'success',
                'data' => $lotacoes,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Erro ao listar as lotações. Verifique os parâmetros ou tente novamente mais tarde.',
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
    public function store(LotacaoStoreRequest $request)
    {
        if ($lotacao = $this->lotacaoStoreService->handle($request->all())) {
            return response()->json([
                'status' => 'success',
                'data' => $lotacao,
                'message' => 'Lotação criada com sucesso'
            ], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Erro ao criar lotação. Verifique os dados enviados.'
        ], 422);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        if ($lotacao = $this->lotacaoFindService->handle($id)) {
            return response()->json([
                'status' => 'success',
                'data' => $lotacao,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Lotação não encontrada. Verifique o ID fornecido.',
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
    public function update(LotacaoStoreRequest $request, string $id)
    {
        if ($lotacao = $this->lotacaoUpdateService->handle($id, $request->all())) {
            return response()->json([
                'status' => 'success',
                'message' => 'Lotação atualizada com sucesso.',
                'data' => $lotacao,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Lotação não encontrada ou erro ao atualizar.',
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lotacao = $this->lotacaoDestroyService->handle($id);

        if ($lotacao) {
            return response()->json([
                'status' => 'success',
                'message' => 'Lotação deletada com sucesso.',
                'data' => $lotacao,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Lotação não encontrada ou erro ao deletar.',
        ], 404);
    }
}
