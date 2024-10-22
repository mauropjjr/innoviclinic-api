<?php 

namespace App\Http\Public;

use App\Models\Empresa;
use App\Services\AgendaService;
use App\Services\CustomAuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class EmpresaPublic
{
    protected $customAuthService;

    public function __construct(CustomAuthService $customAuthService) {
        $this->customAuthService = $customAuthService;
    }

    public function index(Request $request) {
        $request->validate([
            "with" => "required|array",
            "with.*" => "string"
        ]);

        $objeto = Empresa::with($request->with)->findOr($request->id, ["*"], function() {
            return response()->json([
                "error" => "Não encontrado"
            ], Response::HTTP_NOT_FOUND);
        });
        
        return response()->json($objeto);
    }
}