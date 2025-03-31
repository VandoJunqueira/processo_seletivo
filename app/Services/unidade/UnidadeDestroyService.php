<?php

namespace App\Services\Unidade;

use App\Models\Unidade;
use Illuminate\Support\Facades\Log;

class UnidadeDestroyService
{
    public function handle(int $id)
    {
        try {
            $unidade = Unidade::find($id);

            if (!$unidade) {
                return null;
            }

            $unidade->load('endereco');

            $unidade->delete();

            return $unidade;
        } catch (\Throwable $th) {
            Log::error('Erro ao tentar deletar a unidade', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'id' => $id,
            ]);

            return null;
        }
    }
}
