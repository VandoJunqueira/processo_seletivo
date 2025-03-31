<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ServidorEfetivoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'se_matricula' => $this->se_matricula,
            'id' => $this->pessoa->pes_id,
            'pes_nome' => $this->pessoa->pes_nome,
            'pes_data_nascimento' => $this->pessoa->pes_data_nascimento,
            'pes_sexo' => $this->pessoa->pes_sexo,
            'pes_mae' => $this->pessoa->pes_mae,
            'pes_pai' => $this->pessoa->pes_pai,
            'endereco' => $this->pessoa->endereco,
            'lotacao' => $this->whenLoaded('lotacaoAtiva', function () {
                return [
                    'unidade' => $this->lotacaoAtiva->unidade->unid_nome ?? null,
                    'data_lotacao' => $this->lotacaoAtiva->lot_data_lotacao ?? null,
                    'portaria' => $this->lotacaoAtiva->lot_portaria ?? null
                ];
            })
        ];
    }
}
