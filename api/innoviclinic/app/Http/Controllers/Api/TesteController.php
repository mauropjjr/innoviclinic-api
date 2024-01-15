<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTesteRequest;
use App\Http\Requests\UpdateTesteRequest;
use App\Models\Teste;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TesteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $products = Product::latest()->paginate(5);
        $testes = Teste::where('id', '>', 0)->simplePaginate(15);

        return response()->json($testes, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['testes' => []], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(StoreTesteRequest  $request)
     {
       // $input = $request->all();
       $input = $request->validated();
  
  
        $teste = Teste::create($input);

        return response()->json([
            "message" => "Teste criado com sucesso.",
            "data" => $teste
        ]);
     }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (!$teste = Teste::findOrFail($id)) {
            return response()->json([
                'error' => 'Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($teste);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTesteRequest $request, string $id)
    {
        
       
        if (!$teste = Teste::find($id)) {
    
            return response()->json([
                'error' => 'Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
       
        $input = $request->validated();

        $teste->update($input);
   
        return response()->json([
            "success" => true,
            "message" => "Teste alterado com sucesso.",
            "data" => $teste
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teste $teste)
    {
        $teste->delete();
   
        return response()->json([
            "message" => "Teste removido com sucesso successfully.",
            "data" => $teste
        ]);
    }
}
