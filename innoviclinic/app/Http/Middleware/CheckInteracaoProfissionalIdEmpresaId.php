<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Interacao;
use App\Services\CustomAuthService;
use Symfony\Component\HttpFoundation\Response;

class CheckInteracaoProfissionalIdEmpresaId
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
        if (!$this->checkInteracaoProfissionalIdEmpresaId($request->route('id'))) {
            return response()->json(['error' => 'Acesso não permitido'], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }

    private function checkInteracaoProfissionalIdEmpresaId($id)
    {
        $user = $this->customAuth->getUser();
        if ($user->tipo_usuario != 'Profissional') {
            return false;
        }
        if (!$user->empresa_profissional) {
            return false;
        }

        return Interacao::where('id', $id)
            ->with(['prontuario' => function ($query) use ($user) {
                $query->where('profissional_id', $user->empresa_profissional->profissional_id)
                    ->where('empresa_id', $user->empresa_profissional->empresa_id);
            }])
            ->exists();
    }
}
