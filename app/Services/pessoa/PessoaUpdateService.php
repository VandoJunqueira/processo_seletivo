<?php

namespace App\Services\Pessoa;

use App\Models\Pessoa;
use App\Services\Endereco\EnderecoStoreService;
use App\Services\Endereco\EnderecoUpdateService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PessoaUpdateService
{
    public function __construct(private EnderecoUpdateService $enderecoUpdateService) {}

    public function handle(Pessoa $pessoa, array $data)
    {
        DB::beginTransaction();

        try {
            $endereco = $pessoa->endereco()->first();

            $endereco = $this->enderecoUpdateService->handle($endereco, $data);

            $pessoa->update([
                'pes_nome' => $data['nome'] ?? $pessoa->pes_nome,
                'pes_data_nascimento' => $data['data_nascimento'] ?? $pessoa->pes_data_nascimento,
                'pes_sexo' => $data['sexo'] ?? $pessoa->pes_sexo,
                'pes_mae' => $data['mae'] ?? $pessoa->pes_mae,
                'pes_pai' => $data['pai'] ?? $pessoa->pes_pai
            ]);

            DB::commit();

            return $pessoa;
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Erro ao cadastrar endereco', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'input_data' => $data['endereco']
            ]);

            return null;
        }
    }
}
