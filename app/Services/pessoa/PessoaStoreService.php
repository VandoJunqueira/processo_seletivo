<?php

namespace App\Services\Pessoa;

use App\Models\Pessoa;
use App\Services\Endereco\EnderecoStoreService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PessoaStoreService
{
    public function __construct(private EnderecoStoreService $enderecoStoreService) {}

    public function handle(array $data)
    {
        DB::beginTransaction();

        try {
            $endereco = $this->enderecoStoreService->handle($data);

            $pessoa = Pessoa::create([
                'pes_nome' => $data['nome'],
                'pes_data_nascimento' => $data['data_nascimento'],
                'pes_sexo' => $data['sexo'],
                'pes_mae' => $data['mae'],
                'pes_pai' => $data['pai']
            ]);

            $pessoa->endereco()->attach($endereco->end_id);

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
