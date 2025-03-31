<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class UnidadeServidorEfetivoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nome' => $this->servidorEfetivo->pessoa->pes_nome,
            'idade' => $this->servidorEfetivo->pessoa->idade,
            'unidade_lotacao' => $this->unidade->unid_nome
        ];
    }
}
