<?php

namespace App\Http\Controllers\Api;

use App\Models\Procedimento;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProcedimentoRequest;

class ProcedimentoController extends Controller
{

    public function index(Request $request)
    {
        $query = Procedimento::query();
        if ($request->has('ativo') && in_array($request->input('ativo'), ['1', '0'])) {
            $query->where('ativo', $request->input('ativo'));
        }
        return response()->json($query->get());
    }

    public function show($id)
    {
        if (!$objeto = Procedimento::find($id)) {
            return response()->json([
                'error' => 'Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }

     /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProcedimentoRequest  $request)
    {
        $input = $request->validated();
        $objeto = Procedimento::create($input);
        return response()->json($objeto);
    }

    public function update(StoreProcedimentoRequest $request, string $id)
    {
        if (!$objeto = Procedimento::find($id)) {

            return response()->json([
                'error' => 'Not Found'
            ], Response::HTTP_NOT_FOUND);
        }

        $objeto->update($request->all());
        return response()->json($objeto);
    }

    public function destroy(string $id)
    {

        if (!$objeto = Procedimento::find($id)) {

            return response()->json([
                'error' => 'Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }

}
