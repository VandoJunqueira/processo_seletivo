<?php

namespace App\Services\lotacao;

use App\Models\Lotacao;
use Illuminate\Support\Facades\Log;

class LotacaoListService
{
    public function handle(int $perPage = 15)
    {
        try {
            return Lotacao::simplePaginate($perPage);
        } catch (\InvalidArgumentException $e) {
            Log::error('Erro de parâmetro inválido na listagem de lotações', [
                'error' => $e->getMessage(),
                'per_page' => $perPage,
            ]);
            return null;
        } catch (\Throwable $th) {
            Log::error('Erro ao listar as lotações', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'per_page' => $perPage,
            ]);
            return null;
        }
    }
}
