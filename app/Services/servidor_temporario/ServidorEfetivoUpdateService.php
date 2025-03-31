<?php

namespace App\Services\servidor_temporario;

use App\Http\Resources\ServidorTemporarioResource;
use App\Models\Pessoa;
use App\Services\Pessoa\PessoaUpdateService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServidorTemporarioUpdateService
{
    public function __construct(private PessoaUpdateService $pessoaUpdateService) {}

    public function handle(int $id, array $data)
    {
        DB::beginTransaction();

        try {

            $pessoa = Pessoa::findOrFail($id);

            $this->pessoaUpdateService->handle($pessoa, $data);

            $servidorTemporario = $pessoa->servidorTemporario;

            $servidorTemporario->update([
                'st_matricula' => $data['matricula'] ?? $servidorTemporario->st_matricula
            ]);

            DB::commit();

            return new ServidorTemporarioResource($servidorTemporario);
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Erro ao atualizar servidor temporário', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'input_data' => $data
            ]);

            return null;
        }
    }
}
