<?php

namespace App\Services\Unidade;

use App\Models\Unidade;
use Illuminate\Support\Facades\Log;

class UnidadeFindService
{
    public function handle(int $id)
    {
        try {
            return Unidade::with('endereco')->find($id);
        } catch (\Throwable $th) {
            Log::error('Erro ao tentar encontrar a unidade', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'id' => $id,
            ]);

            return null;
        }
    }
}
