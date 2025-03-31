<?php

namespace App\Services\servidor_temporario;

use App\Http\Resources\ServidorTemporarioResource;
use App\Models\ServidorTemporario;
use App\Services\Pessoa\PessoaStoreService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServidorTemporarioStoreService
{

    public function __construct(private PessoaStoreService $pessoaStoreService) {}

    public function handle(array $data)
    {
        DB::beginTransaction();

        try {

            $pessoa = $this->pessoaStoreService->handle($data);

            $servidorTemporario = ServidorTemporario::create([
                'pes_id' => $pessoa->pes_id,
                'st_data_admissao' => $data['data_admissao'],
                'st_data_demissao' => $data['data_demissao'] ?? null
            ]);

            DB::commit();

            return new ServidorTemporarioResource($servidorTemporario);
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Erro ao cadastrar servidor temporário', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'input_data' => $data
            ]);

            return null;
        }
    }

    /**
     * Gera uma matrícula automática
     */
    protected function gerarMatricula(): string
    {
        return 'ST' . now()->format('Ymd') . str_pad((ServidorTemporario::latest('pes_id')->first()->pes_id ?? 0) + 1, 4, '0', STR_PAD_LEFT);
    }
}
