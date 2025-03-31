<?php

namespace App\Services\servidor_temporario;

use App\Http\Resources\ServidorTemporarioResource;
use App\Models\Pessoa;
use Illuminate\Support\Facades\Log;

class ServidorTemporarioFindService
{
    public function handle(int $id)
    {
        try {
            $pessoa = Pessoa::find($id);

            $servidorTemporario = $pessoa->servidorTemporario;

            if (is_null($pessoa) || is_null($servidorTemporario)) {
                return null;
            }

            return new ServidorTemporarioResource($servidorTemporario);
        } catch (\Throwable $th) {
            Log::error('Erro ao tentar encontrar o servidor temporário', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'id' => $id,
            ]);

            return null;
        }
    }
}
