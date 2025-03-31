<?php

namespace App\Http\Controllers;

use App\Services\search\SearchServidorService;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function __construct(private SearchServidorService $searchServidorService) {}

    public function  servidor(Request $request)
    {
        if ($search = $this->searchServidorService->handle($request->nome)) {
            return response()->json([
                'status' => 'success',
                'data' => $search,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Erro ao fazer a busca.',
        ], 422);
    }
}
