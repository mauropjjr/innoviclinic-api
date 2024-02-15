<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\InteracaoAtendimento;
use App\Services\CustomAuthService;
use Symfony\Component\HttpFoundation\Response;

class CheckInteracaoAtendimentoFinalizada
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
        if ($request->isMethod('put') && $this->checkInteracaoAtendimentoFinalizada($request->route('id'))) {
            return response()->json(['error' => 'Alteração não permitido, atendimento já está finalizado'], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }

    private function checkInteracaoAtendimentoFinalizada($id)
    {
        return InteracaoAtendimento::with(['interacao' => function ($query) {
            $query->where('finalizado', 1);
        }])
        ->where('id', $id)
        ->whereHas('interacao', function ($query) {
            $query->where('finalizado', 1);
        })
        ->exists();
    }
}
