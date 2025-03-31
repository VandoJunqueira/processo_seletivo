<?php

namespace App\Services\Unidade;

use App\Models\Unidade;
use Illuminate\Support\Facades\Log;

class UnidadeListService
{
    public function handle(int $perPage = 15)
    {
        try {
            return Unidade::with('endereco')->simplePaginate($perPage);
        } catch (\InvalidArgumentException $e) {
            Log::error('Erro de parâmetro inválido na listagem de unidades', [
                'error' => $e->getMessage(),
                'per_page' => $perPage,
            ]);
            return null;
        } catch (\Throwable $th) {
            Log::error('Erro ao listar as unidades', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'per_page' => $perPage,
            ]);
            return null;
        }
    }
}
