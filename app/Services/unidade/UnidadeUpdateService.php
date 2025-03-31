<?php

namespace App\Services\Unidade;

use App\Models\Unidade;
use App\Services\Endereco\EnderecoUpdateService;
use Illuminate\Support\Facades\Log;

class UnidadeUpdateService
{
    public function __construct(private EnderecoUpdateService $enderecoUpdateService) {}

    public function handle(int $id, array $data)
    {
        try {
            $unidade = Unidade::find($id);

            $endereco = $unidade->endereco()->first();

            $endereco = $this->enderecoUpdateService->handle($endereco, $data);

            if (!$unidade) {
                return null;
            }

            $unidade->update([
                'unid_nome' => $data['nome'],
                'unid_sigla' => $data['sigla'],
            ]);

            $unidade->load('endereco');

            return $unidade;
        } catch (\Throwable $th) {
            Log::error('Erro ao tentar atualizar a unidade', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'id' => $id,
            ]);

            return null;
        }
    }
}
