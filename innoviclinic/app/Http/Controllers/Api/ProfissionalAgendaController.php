<?php

namespace App\Http\Controllers\Api;

use App\Models\ProfissionalAgenda;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProfissionalAgendaRequest;

class ProfissionalAgendaController extends Controller
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;

        $this->middleware('check-profissional-id-agenda-empresa-id')->only(['show', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $user = $this->customAuth->getUser();
        $query = ProfissionalAgenda::query()->with(['profissional:id,nome,email']);
        $query->where('empresa_id', $user->empresa_profissional->empresa_id);

        if ($request->has('profissional_id') && $request->input('profissional_id')) {
            $query->where('profissional_id', $request->input('profissional_id'));
        } else {
            $query->where('profissional_id', $user->empresa_profissional->profissional_id);
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        if (!$objeto = ProfissionalAgenda::with(['profissional:id,nome,email'])->find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }

    public function store(StoreProfissionalAgendaRequest  $request)
    {
        $input = $request->validated();
        $objeto = ProfissionalAgenda::create($input);
        return response()->json($objeto);
    }

    public function update(StoreProfissionalAgendaRequest $request, string $id)
    {
        if (!$objeto = ProfissionalAgenda::find($id)) {
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
        if (!$objeto = ProfissionalAgenda::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }
}
