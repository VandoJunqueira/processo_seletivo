<?php

namespace App\Services\Unidade;

use App\Http\Resources\UnidadeServidorEfetivoResource;
use App\Models\Lotacao;
use Illuminate\Support\Facades\Log;

class UnidadeServidoresEfetivosService
{
    public function handle(int $perPage = 15)
    {
        try {
            $lotacoes = Lotacao::with(['servidorEfetivo', 'unidade'])->simplePaginate($perPage);

            return [
                'data' => UnidadeServidorEfetivoResource::collection($lotacoes),
                'per_page' => $perPage,
                'current_page' => $lotacoes->currentPage(),
                'first_page_url' => $lotacoes->url(1),
                'next_page_url' => $lotacoes->nextPageUrl(),
                'prev_page_url' => $lotacoes->previousPageUrl(),
                'path' => $lotacoes->path(),
                'from' => $lotacoes->firstItem(),
                'to' => $lotacoes->lastItem(),
            ];
        } catch (\InvalidArgumentException $e) {
            Log::error('Erro de parâmetro inválido na listagem', [
                'error' => $e->getMessage(),
                'per_page' => $perPage,
            ]);
            return null;
        } catch (\Throwable $th) {
            Log::error('Erro ao listar', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'per_page' => $perPage,
            ]);
            return null;
        }
    }
}
