<?php

namespace App\Services\servidor_efetivo;

use App\Http\Resources\ServidorEfetivoResource;
use App\Models\ServidorEfetivo;
use App\Services\Pessoa\PessoaStoreService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServidorEfetivoStoreService
{

    public function __construct(private PessoaStoreService $pessoaStoreService) {}

    public function handle(array $data)
    {
        DB::beginTransaction();

        try {

            $pessoa = $this->pessoaStoreService->handle($data);

            $servidorEfetivo = ServidorEfetivo::create([
                'pes_id' => $pessoa->pes_id,
                'se_matricula' => $data['matricula'] ?? $this->gerarMatricula()
            ]);

            DB::commit();

            return new ServidorEfetivoResource($servidorEfetivo);
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Erro ao cadastrar servidor efetivo', [
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
        return 'SE' . now()->format('Ymd') . str_pad((ServidorEfetivo::latest('pes_id')->first()->pes_id ?? 0) + 1, 4, '0', STR_PAD_LEFT);
    }
}
