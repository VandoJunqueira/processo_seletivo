<?php

namespace App\Services\Endereco;

use App\Models\Cidade;
use App\Models\Endereco;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnderecoUpdateService
{
    public function handle(Endereco $endereco, array $data)
    {
        DB::beginTransaction();

        try {
            $cidade = Cidade::firstOrCreate(
                ['cid_nome' => $data['endereco']['cidade']['nome']],
                ['cid_uf' => $data['endereco']['cidade']['uf']]
            );

            $endereco->update([
                'end_tipo_logradouro' => $data['endereco']['tipo_logradouro'],
                'end_logradouro' => $data['endereco']['logradouro'],
                'end_numero' => $data['endereco']['numero'],
                'end_bairro' => $data['endereco']['bairro'],
                'cid_id' => $cidade->cid_id
            ]);

            DB::commit();

            return $endereco;
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Erro ao atualizar endereco', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'input_data' => $data['endereco']
            ]);

            return null;
        }
    }
}
