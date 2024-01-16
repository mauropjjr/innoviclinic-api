<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProcedimentoTipoRequest;
use App\Models\ProcedimentoTipo;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProcedimentoTipoController extends Controller
{

    public function index()
    {
        $user = Auth::user();
          // $products = Product::latest()->paginate(5);
        $objetos = ProcedimentoTipo::where(['usuario_id' => $user->id])->simplePaginate(15);

        return response()->json($objetos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProcedimentoTipoRequest  $request)
    {
        $input = $request->validated();
        $user = Auth::user();
        $input['ativo'] = true;
        $input['usuario_id'] = $user->id;

        $objeto = ProcedimentoTipo::create($input);
        return response()->json($objeto);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (!$objeto = ProcedimentoTipo::find($id)) {
            return response()->json([
                'error' => 'Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }


    public function update(StoreProcedimentoTipoRequest $request, string $id)
    {
        if (!$objeto = ProcedimentoTipo::find($id)) {

            return response()->json([
                'error' => 'Not Found'
            ], Response::HTTP_NOT_FOUND);
        }

        $objeto->update($request->all());
        return response()->json($objeto);
    }


    public function destroy(string $id)
    {

        if (!$objeto = ProcedimentoTipo::find($id)) {

            return response()->json([
                'error' => 'Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }
}
