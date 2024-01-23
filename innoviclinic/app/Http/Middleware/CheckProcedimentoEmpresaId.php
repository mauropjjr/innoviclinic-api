<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Procedimento;
use App\Services\CustomAuthService;
use Symfony\Component\HttpFoundation\Response;

class CheckProcedimentoEmpresaId
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
        $user = $this->customAuth->getUser();
        if (!$user || !$this->checkProcedimentoEmpresaId($request->route('id'), $user)) {
            return response()->json(['error' => 'Acesso não permitido'], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }

    private function checkProcedimentoEmpresaId($id, $user)
    {
        return Procedimento::where('id', $id)->where('empresa_id', $user->empresa_profissional->empresa_id)->exists();
    }
}
