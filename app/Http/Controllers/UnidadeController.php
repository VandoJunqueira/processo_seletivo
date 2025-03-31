<?php

namespace App\Http\Controllers;

use App\Http\Requests\unidade\UnidadeListRequest;
use App\Http\Requests\unidade\UnidadeStoreRequest;
use App\Services\Unidade\UnidadeDestroyService;
use App\Services\unidade\UnidadeFindService;
use App\Services\Unidade\UnidadeListService;
use App\Services\unidade\UnidadeStoreService;
use App\Services\Unidade\UnidadeUpdateService;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{

    public function __construct(
        private UnidadeListService $unidadeListService,
        private UnidadeStoreService $unidadeStoreService,
        private UnidadeFindService $unidadeFindService,
        private UnidadeDestroyService $unidadeDestroyService,
        private UnidadeUpdateService $unidadeUpdateService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(UnidadeListRequest $request)
    {
        $perPage = $request->input('per_page', 15); // Se não for informado, o padrão será 15

        if ($unidades = $this->unidadeListService->handle($perPage)) {
            return response()->json([
                'status' => 'success',
                'data' => $unidades,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Erro ao listar as unidades. Verifique os parâmetros ou tente novamente mais tarde.',
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
    public function store(UnidadeStoreRequest $request)
    {
        if ($unidade = $this->unidadeStoreService->handle($request->all())) {
            return response()->json([
                'status' => 'success',
                'data' => $unidade,
                'message' => 'Unidade criada com sucesso'
            ], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Erro ao criar unidade. Verifique os dados enviados.'
        ], 422);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        if ($unidade = $this->unidadeFindService->handle($id)) {
            return response()->json([
                'status' => 'success',
                'data' => $unidade,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unidade não encontrada. Verifique o ID fornecido.',
        ], 404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnidadeStoreRequest $request, string $id)
    {
        if ($unidade = $this->unidadeUpdateService->handle($id, $request->all())) {
            return response()->json([
                'status' => 'success',
                'message' => 'Unidade atualizada com sucesso.',
                'data' => $unidade,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unidade não encontrada ou erro ao atualizar.',
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unidade = $this->unidadeDestroyService->handle($id);

        if ($unidade) {
            return response()->json([
                'status' => 'success',
                'message' => 'Unidade deletada com sucesso.',
                'data' => $unidade,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unidade não encontrada ou erro ao deletar.',
        ], 404);
    }
}
