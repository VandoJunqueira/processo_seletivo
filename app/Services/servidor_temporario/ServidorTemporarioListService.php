<?php

namespace App\Services\servidor_temporario;

use App\Http\Resources\ServidorTemporarioResource;
use App\Models\ServidorTemporario;
use Illuminate\Support\Facades\Log;

class ServidorTemporarioListService
{
    public function handle(int $perPage = 15)
    {
        try {
            $servidoresTemporarios = ServidorTemporario::simplePaginate($perPage);

            return [
                'data' => ServidorTemporarioResource::collection($servidoresTemporarios),
                'per_page' => $perPage,
                'current_page' => $servidoresTemporarios->currentPage(),
                'first_page_url' => $servidoresTemporarios->url(1),
                'next_page_url' => $servidoresTemporarios->nextPageUrl(),
                'prev_page_url' => $servidoresTemporarios->previousPageUrl(),
                'path' => $servidoresTemporarios->path(),
                'from' => $servidoresTemporarios->firstItem(),
                'to' => $servidoresTemporarios->lastItem(),
            ];
        } catch (\InvalidArgumentException $e) {
            Log::error('Erro de parâmetro inválido na listagem de servidores temporários', [
                'error' => $e->getMessage(),
                'per_page' => $perPage,
            ]);
            return null;
        } catch (\Throwable $th) {
            Log::error('Erro ao listar os servidores temporários', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'per_page' => $perPage,
            ]);
            return null;
        }
    }
}
