<?php

namespace App\Http\Middleware;

use App\Models\EmpresaProfissional;
use Closure;
use App\Services\CustomAuthService;
use Symfony\Component\HttpFoundation\Response;

class CheckEmpresaProfissionalId
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
        if (!$this->checkEmpresaProfissionald($request)) {
            return response()->json(['error' => 'Acesso não permitido'], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }

    private function checkEmpresaProfissionald($request)
    {
        $user = $this->customAuth->getUser();
        if (!$user->empresa_profissional) {
            return false;
        }

        $id = $request->route('id');
        //$rota = $request->route()->getName();

        if($user->tipo_usuario != 'Profissional'){
            return false;
        }

        //Sendo da mesma empresa tem permissão
        if($id){
            return EmpresaProfissional::where('profissional_id', $id)
            ->where('empresa_id', $user->empresa_profissional->empresa_id)
            //->where('profissional_id', $user->empresa_profissional->profissional_id)
            ->exists();
        } else {
            return EmpresaProfissional::where('empresa_id', $user->empresa_profissional->empresa_id)
            //->where('profissional_id', $user->empresa_profissional->profissional_id)
            ->exists();
        }
    }
}
