<?php


namespace App\Services\unidade;

use App\Models\Unidade;
use App\Services\Endereco\EnderecoStoreService;
use Illuminate\Support\Facades\Log;

class UnidadeStoreService
{
    public function __construct(private EnderecoStoreService $enderecoStoreService) {}

    public function handle(array $data)
    {
        try {

            $endereco = $this->enderecoStoreService->handle($data);

            $unidade = Unidade::create([
                'unid_nome' => $data['nome'],
                'unid_sigla' => $data['sigla'],
            ]);


            $unidade->endereco()->attach($endereco->end_id);

            $unidade->load('endereco');

            return $unidade;
        } catch (\Throwable $th) {
            Log::error('Erro ao criar unidade', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            return null;
        }
    }
}
