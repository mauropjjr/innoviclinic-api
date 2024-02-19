<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Interacao;
use App\Models\EmpresaProfissional;
use App\Services\CustomAuthService;
use App\Models\InteracaoAtendimento;
use App\Models\ProfissionalSecretaria;
use Symfony\Component\HttpFoundation\Response;

class CheckAgendaProfissionalIdEmpresaId
{
    protected $customAuth;

    public function __construct(CustomAuthService $customAuth)
    {
        $this->customAuth = $customAuth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next): Response
    {
        if (!$this->checkAgendaProfissionalIdEmpresaId($request)) {
            return response()->json(['error' => 'Acesso não permitido'], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }

    private function checkAgendaProfissionalIdEmpresaId($request)
    {
        $user = $this->customAuth->getUser();
        $profissionalIdData = $request->input('profissional_id');
        if (!$user->empresa_profissional) {
            return false;
        }

        //O profissinal_id é da empresa do usuário logado?
        if ($user->tipo_usuario == 'Profissional') {
            return EmpresaProfissional::where('profissional_id', $profissionalIdData)
                ->where('empresa_id', $user->empresa_profissional->empresa_id)
                ->exists();
        } else {
            return ProfissionalSecretaria::where('secretaria_id', $user->id)
                ->where('empresa_id', $user->empresa_profissional->empresa_id)
                ->where('profissional_id', $profissionalIdData)
                ->exists();
        }
    }
}
