<?php

namespace App\Http\Controllers\Api;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreEmpresaRequest;

class EmpresaController extends Controller
{

    public function index(Request $request)
    {
        $query = Empresa::query();
        if ($request->has('ativo') && in_array($request->input('ativo'), ['1', '0'])) {
            $query->where('ativo', $request->input('ativo'));
        }
        return response()->json($query->get());
    }

    public function show($id)
    {
        if (!$objeto = Empresa::find($id)) {
            return response()->json([
                'error' => 'Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }

    public function store(StoreEmpresaRequest  $request)
    {
        $input = $request->validated();
        $objeto = Empresa::create($input);
        return response()->json($objeto);
    }

    public function update(StoreEmpresaRequest $request, string $id)
    {
        if (!$objeto = Empresa::find($id)) {
            return response()->json([
                'error' => 'Not Found'
            ], Response::HTTP_NOT_FOUND);
        }

        $objeto->update($request->all());
        return response()->json($objeto);
    }

    public function destroy(string $id)
    {
        if (!$objeto = Empresa::find($id)) {
            return response()->json([
                'error' => 'Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }
}
