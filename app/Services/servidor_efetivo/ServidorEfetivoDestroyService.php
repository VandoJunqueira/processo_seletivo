<?php

namespace App\Services\servidor_efetivo;

use App\Http\Resources\ServidorEfetivoResource;
use App\Models\ServidorEfetivo;
use App\Models\Pessoa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServidorEfetivoDestroyService
{
    public function handle(int $id)
    {
        DB::beginTransaction();

        try {

            $pessoa = Pessoa::with('servidorEfetivo', 'endereco')->find($id);

            if (!$pessoa) {
                DB::rollBack();
                Log::warning('Servidor efetivo não encontrado para exclusão', ['id' => $id]);
                return null;
            }

            $pessoa->servidorEfetivo;

            ServidorEfetivo::where('pes_id', $id)->delete();

            $pessoa->endereco()->detach();

            foreach ($pessoa->endereco as $endereco) {
                $endereco->delete();
            }

            $pessoa->delete();

            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Falha ao excluir servidor efetivo', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'id' => $id
            ]);

            return null;
        }
    }
}
