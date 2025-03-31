<?php

namespace App\Services\servidor_efetivo;

use App\Http\Resources\ServidorEfetivoResource;
use App\Models\Pessoa;
use App\Services\Pessoa\PessoaUpdateService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServidorEfetivoUpdateService
{
    public function __construct(private PessoaUpdateService $pessoaUpdateService) {}

    public function handle(int $id, array $data)
    {
        DB::beginTransaction();

        try {

            $pessoa = Pessoa::findOrFail($id);

            $this->pessoaUpdateService->handle($pessoa, $data);

            $servidorEfetivo = $pessoa->servidorEfetivo;

            $servidorEfetivo->update([
                'se_matricula' => $data['matricula'] ?? $servidorEfetivo->se_matricula
            ]);

            DB::commit();

            return new ServidorEfetivoResource($servidorEfetivo);
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Erro ao atualizar servidor efetivo', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'input_data' => $data
            ]);

            return null;
        }
    }
}
