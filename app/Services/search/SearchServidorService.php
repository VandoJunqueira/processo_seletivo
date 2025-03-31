<?php

namespace App\Services\search;

use App\Http\Resources\SearchServidorResource;
use App\Models\ServidorEfetivo;

class SearchServidorService
{
    public function handle(string $nome, int $perPage = 15)
    {

        $servidor = ServidorEfetivo::with([
            'pessoa',
            'lotacaoAtiva.unidade.endereco.cidade'
        ])
            ->whereHas('pessoa', function ($query) use ($nome) {
                $query->where('pes_nome', 'like', "%{$nome}%");
            })
            ->whereHas('lotacaoAtiva')
            ->paginate($perPage);


        return [
            'data' => SearchServidorResource::collection($servidor),
            'per_page' => $perPage,
            'current_page' => $servidor->currentPage(),
            'first_page_url' => $servidor->url(1),
            'next_page_url' => $servidor->nextPageUrl(),
            'prev_page_url' => $servidor->previousPageUrl(),
            'path' => $servidor->path(),
            'from' => $servidor->firstItem(),
            'to' => $servidor->lastItem(),
        ];
    }
}
