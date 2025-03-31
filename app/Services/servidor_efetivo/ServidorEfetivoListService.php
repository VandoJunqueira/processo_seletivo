<?php

namespace App\Services\servidor_efetivo;

use App\Http\Resources\ServidorEfetivoResource;
use App\Models\ServidorEfetivo;
use Illuminate\Support\Facades\Log;

class ServidorEfetivoListService
{
    public function handle(int $perPage = 15)
    {
        try {
            $servidoresEfetivos = ServidorEfetivo::simplePaginate($perPage);

            return [
                'data' => ServidorEfetivoResource::collection($servidoresEfetivos),
                'per_page' => $perPage,
                'current_page' => $servidoresEfetivos->currentPage(),
                'first_page_url' => $servidoresEfetivos->url(1),
                'next_page_url' => $servidoresEfetivos->nextPageUrl(),
                'prev_page_url' => $servidoresEfetivos->previousPageUrl(),
                'path' => $servidoresEfetivos->path(),
                'from' => $servidoresEfetivos->firstItem(),
                'to' => $servidoresEfetivos->lastItem(),
            ];
        } catch (\InvalidArgumentException $e) {
            Log::error('Erro de parâmetro inválido na listagem de servidores efetivos', [
                'error' => $e->getMessage(),
                'per_page' => $perPage,
            ]);
            return null;
        } catch (\Throwable $th) {
            Log::error('Erro ao listar os servidores efetivos', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'per_page' => $perPage,
            ]);
            return null;
        }
    }
}
