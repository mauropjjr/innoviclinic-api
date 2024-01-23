<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use Closure;
use App\Services\CustomAuthService;
use Symfony\Component\HttpFoundation\Response;

class CheckEmpresaId
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
        if (!$user || !$this->CheckEmpresaId($request->route('id'), $user)) {
            return response()->json(['error' => 'Acesso não permitido'], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }

    private function CheckEmpresaId($id, $user)
    {
        return Empresa::where('id', $id)->where('id', $user->empresa_profissional->empresa_id)->exists();
    }
}
