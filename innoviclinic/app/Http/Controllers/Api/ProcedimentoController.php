<?php

namespace App\Http\Controllers\Api;

use App\Models\Procedimento;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Services\CustomAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProcedimentoRequest;

class ProcedimentoController extends Controller
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;

        $this->middleware('check-procedimento-empresa-id')->only(['show', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $user = $this->customAuth->getUser();
        $query = Procedimento::query();
        $query->where('empresa_id', $user->empresa_profissional->empresa_id);

        if ($request->has('nome') && $request->input('nome')) {
            $query->where('nome', 'LIKE', "%" . $request->input('nome') . "%");
        }
        if ($request->has('ativo') && in_array($request->input('ativo'), ['1', '0'])) {
            $query->where('ativo', $request->input('ativo'));
        }
        return response()->json($query->with([
            'procedimento_tipo'])->get());
    }

    public function show($id)
    {
        if (!$objeto = Procedimento::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($objeto);
    }

    public function store(StoreProcedimentoRequest $request)
{
    try {
        DB::beginTransaction();

        // Validação dos dados do request
        $input = $request->validated();

        // Criar o procedimento
        $procedimento = Procedimento::create($input);

        // Adicionar os convênios associados ao procedimento
        $conveniosData = $request->input('convenios');
        if (isset($conveniosData) && is_array($conveniosData)) {
            $convenios = [];
            foreach ($conveniosData as $data) {
                $convenios[$data['convenio_id']] = [
                    'valor' => $data['valor'],
                    'ativo' => $data['ativo'] ?? true,
                ];
            }
            $procedimento->procedimento_convenios()->syncWithPivotValues($convenios, ['valor', 'ativo'], false);
        }

        DB::commit();

        return response()->json($procedimento);
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}

    public function update(StoreProcedimentoRequest $request, string $id)
    {
        if (!$objeto = Procedimento::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        $objeto->update($request->all());
        return response()->json($objeto);
    }

    public function destroy(string $id)
    {
        if (!$objeto = Procedimento::find($id)) {
            return response()->json([
                'error' => 'Não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $objeto->delete();

        return response()->noContent(Response::HTTP_CREATED);
    }
}
