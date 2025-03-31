<?php

namespace App\Services\servidor_efetivo;

use App\Http\Resources\ServidorEfetivoResource;
use App\Models\Pessoa;
use Illuminate\Support\Facades\Log;

class ServidorEfetivoFindService
{
    public function handle(int $id)
    {
        try {
            $pessoa = Pessoa::find($id);

            $servidorEfetivo =   $pessoa->servidorEfetivo;

            if (is_null($pessoa) || is_null($servidorEfetivo)) {
                return null;
            }

            return new ServidorEfetivoResource($servidorEfetivo);
        } catch (\Throwable $th) {
            Log::error('Erro ao tentar encontrar o servidor efetivo', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'id' => $id,
            ]);

            return null;
        }
    }
}
