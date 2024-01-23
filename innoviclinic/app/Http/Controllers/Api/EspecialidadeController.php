<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Especialidade;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class EspecialidadeController extends Controller
{

    public function index(Request $request)
{
    $query = Especialidade::query();
    if ($request->has('profissao_id') && is_int($request->input('profissao_id'))) {
        $query->where('profissao_id', $request->input('profissao_id'));
    }
    if ($request->has('ativo') && in_array($request->input('ativo'), ['1', '0'])) {
        $query->where('ativo', $request->input('ativo'));
    }
    return response()->json($query->with('profissao')->get());
}

    public function show($id)
    {
        if (!$objeto = Especialidade::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }

}
