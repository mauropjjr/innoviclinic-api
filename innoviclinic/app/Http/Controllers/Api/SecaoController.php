<?php

namespace App\Http\Controllers\Api;

use App\Models\Secao;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreSecaoRequest;

class SecaoController extends Controller
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;

        $this->middleware('check-secao-profissional-id-empresa-id')->only(['show', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $user = $this->customAuth->getUser();
        $query = Secao::query();
        $query->where('empresa_id', $user->empresa_profissional->empresa_id);
        $query->where('profissional_id', $user->empresa_profissional->profissional_id);

        return response()->json($query->get());
    }

    public function show($id)
    {
        if (!$objeto = Secao::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }

    public function store(StoreSecaoRequest  $request)
    {
        $input = $request->validated();
        $objeto = Secao::create($input);
        return response()->json($objeto);
    }

    public function update(StoreSecaoRequest $request, string $id)
    {
        if (!$objeto = Secao::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $input = $request->validated();
        $objeto->update($input);
        return response()->json($objeto);
    }

    public function destroy(string $id)
    {
        if (!$objeto = Secao::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }
}
