<?php

namespace App\Services\lotacao;

use App\Models\Lotacao;
use Illuminate\Support\Facades\Log;

class LotacaoDestroyService
{
    public function handle(int $id)
    {
        try {
            $lotacao = Lotacao::find($id);

            if (!$lotacao) {
                return null;
            }

            $lotacao->delete();

            return $lotacao;
        } catch (\Throwable $th) {
            Log::error('Erro ao tentar deletar a lotação', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'id' => $id,
            ]);

            return null;
        }
    }
}
