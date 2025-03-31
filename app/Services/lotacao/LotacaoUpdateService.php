<?php

namespace App\Services\lotacao;

use App\Models\Lotacao;
use App\Services\Endereco\EnderecoUpdateService;
use Illuminate\Support\Facades\Log;

class LotacaoUpdateService
{
    public function __construct(private EnderecoUpdateService $enderecoUpdateService) {}

    public function handle(int $id, array $data)
    {
        try {
            $lotacao = Lotacao::find($id);

            if (!$lotacao) {
                return null;
            }

            $lotacao->update([
                'pes_id' => $data['pes_id'] ?? $lotacao->pes_id,
                'unid_id' => $data['unid_id'] ?? $lotacao->unid_id,
                'lot_data_lotacao' => $data['data_lotacao'] ?? $lotacao->lot_data_lotacao,
                'lot_data_remocao' => $data['data_remocao']  ?? $lotacao->lot_data_remocao,
                'lot_portaria' => $data['portaria'] ?? $lotacao->lot_portaria,
            ]);

            return $lotacao;
        } catch (\Throwable $th) {
            Log::error('Erro ao tentar atualizar a lotação', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'id' => $id,
            ]);

            return null;
        }
    }
}
