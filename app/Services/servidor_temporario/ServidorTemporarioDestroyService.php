<?php

namespace App\Services\servidor_temporario;

use App\Http\Resources\ServidorTemporarioResource;
use App\Models\ServidorTemporario;
use App\Models\Pessoa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServidorTemporarioDestroyService
{
    public function handle(int $id)
    {
        DB::beginTransaction();

        try {

            $pessoa = Pessoa::with('servidorTemporario', 'endereco')->find($id);

            if (!$pessoa) {
                DB::rollBack();
                Log::warning('Servidor temporário não encontrado para exclusão', ['id' => $id]);
                return null;
            }

            $pessoa->servidorTemporario;

            ServidorTemporario::where('pes_id', $id)->delete();

            $pessoa->endereco()->detach();

            foreach ($pessoa->endereco as $endereco) {
                $endereco->delete();
            }

            $pessoa->delete();

            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Falha ao excluir servidor temporário', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'id' => $id
            ]);

            return null;
        }
    }
}
