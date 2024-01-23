<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProntuarioRequest;
use App\Models\Prontuario;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProntuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
          // $products = Product::latest()->paginate(5);
        $objetos = Prontuario::latest()->simplePaginate(15);

        return response()->json($objetos, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreProntuarioRequest  $request)
    {
        $input = $request->validated();


        $objeto = Prontuario::create($input);

        return response()->json($objeto);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProntuarioRequest  $request)
    {
        $input = $request->validated();
        $objeto = Prontuario::create($input);
        return response()->json($objeto);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (!$objeto = Prontuario::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProntuarioRequest $request, string $id)
    {


        if (!$objeto = Prontuario::find($id)) {

            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        $input = $request->validated();

        $objeto->update($input);

        return response()->json($objeto);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        if (!$objeto = Prontuario::find($id)) {

            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->json($objeto);
    }
}
