<?php

namespace App\Http\Controllers\Api;

use App\Models\Escolaridade;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class EscolaridadeController extends Controller
{

    public function index(Request $request)
    {
        $query = Escolaridade::query();
        if ($request->has('ativo') && in_array($request->input('ativo'), ['1', '0'])) {
            $query->where('ativo', $request->input('ativo'));
        }
        return response()->json($query->get());
    }

    public function show($id)
    {
        if (!$objeto = Escolaridade::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }

}
