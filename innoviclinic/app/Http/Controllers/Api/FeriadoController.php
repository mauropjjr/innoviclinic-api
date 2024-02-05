<?php

namespace App\Http\Controllers\Api;

use App\Models\Feriado;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreFeriadoRequest;

class FeriadoController extends Controller
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;

        $this->middleware('check-feriado-empresa-id')->only(['show', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $user = $this->customAuth->getUser();
        $query = Feriado::query();

        // Filtra por empresa_id da empresa logada ou feriados nacionais convencionais
        $query->where(function ($query) use ($user) {
            $query->where('empresa_id', $user->empresa_profissional->empresa_id)
                ->orWhereNull('empresa_id');
        });

        if ($request->has('nome') && $request->input('nome')) {
            $query->where('nome', 'LIKE', "%" . $request->input('nome') . "%");
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        if (!$objeto = Feriado::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }

    public function store(StoreFeriadoRequest  $request)
    {
        $input = $request->validated();
        $objeto = Feriado::create($input);
        return response()->json($objeto);
    }

    public function update(StoreFeriadoRequest $request, string $id)
    {
        if (!$objeto = Feriado::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        $objeto->update($request->all());
        return response()->json($objeto);
    }

    public function destroy(string $id)
    {
        if (!$objeto = Feriado::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }
}
