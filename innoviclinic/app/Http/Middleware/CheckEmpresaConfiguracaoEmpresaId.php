<?php

namespace App\Http\Middleware;

use App\Models\EmpresaConfiguracao;
use Closure;
use App\Services\CustomAuthService;
use Symfony\Component\HttpFoundation\Response;

class CheckEmpresaConfiguracaoEmpresaId
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

        if (!$this->checkEmpresaConfiguracaoEmpresaId($request->route('id'))) {
            return response()->json(['error' => 'Acesso não permitido'], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }

    private function checkEmpresaConfiguracaoEmpresaId($id)
    {
        $user = $this->customAuth->getUser();
        if (!$user->empresa_profissional) {
            return false;
        }

        return EmpresaConfiguracao::where('id', $id)->where('empresa_id', $user->empresa_profissional->empresa_id)->exists();
    }
}
