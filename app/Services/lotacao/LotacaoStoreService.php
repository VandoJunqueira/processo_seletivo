<?php

namespace App\Services\lotacao;

use App\Models\Lotacao;
use App\Services\Endereco\EnderecoStoreService;
use Illuminate\Support\Facades\Log;

class LotacaoStoreService
{
    public function __construct(private EnderecoStoreService $enderecoStoreService) {}

    public function handle(array $data)
    {
        try {

            $lotacao = Lotacao::create([
                'pes_id' => $data['pes_id'],
                'unid_id' => $data['unid_id'],
                'lot_data_lotacao' => $data['data_lotacao'],
                'lot_data_remocao' => $data['data_remocao'] ?? null,
                'lot_portaria' => $data['portaria'],
            ]);

            return $lotacao;
        } catch (\Throwable $th) {
            Log::error('Erro ao criar lotação', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            return null;
        }
    }
}
