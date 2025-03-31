<?php

namespace App\Services\lotacao;

use App\Models\Lotacao;
use Illuminate\Support\Facades\Log;

class LotacaoFindService
{
    public function handle(int $id)
    {
        try {
            return Lotacao::find($id);
        } catch (\Throwable $th) {
            Log::error('Erro ao tentar encontrar a lotação', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'id' => $id,
            ]);

            return null;
        }
    }
}
