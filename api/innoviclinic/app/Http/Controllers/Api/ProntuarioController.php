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
        $prontuarios = Prontuario::latest()->simplePaginate(15);

        return response()->json($prontuarios, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreProntuarioRequest  $request)
    {
        $input = $request->validated();


        $teste = Prontuario::create($input);

        return response()->json($teste);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProntuarioRequest  $request)
    {
        $input = $request->validated();
        $Prontuario = Prontuario::create($input);
        return response()->json($Prontuario);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (!$Prontuario = Prontuario::find($id)) {
            return response()->json([
                'error' => 'Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($Prontuario);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProntuarioRequest $request, string $id)
    {


        if (!$Prontuario = Prontuario::find($id)) {

            return response()->json([
                'error' => 'Not Found'
            ], Response::HTTP_NOT_FOUND);
        }

        $input = $request->validated();

        $Prontuario->update($input);

        return response()->json($Prontuario);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        if (!$Prontuario = Prontuario::find($id)) {

            return response()->json([
                'error' => 'Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
        $Prontuario->delete();

        return response()->json([
            "message" => "Prontuario removido com sucesso successfully.",
            "data" => $Prontuario
        ]);
    }
}
