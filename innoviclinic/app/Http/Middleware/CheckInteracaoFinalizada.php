<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Interacao;
use App\Services\CustomAuthService;
use Symfony\Component\HttpFoundation\Response;

class CheckInteracaoFinalizada
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
        if ($request->isMethod('put') && $this->checkInteracaoFinalizada($request->route('id'))) {
            return response()->json(['error' => 'Alteração não permitida, interação já está finalizada'], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }

    private function checkInteracaoFinalizada($id)
    {
        return Interacao::where('id', $id)->where('finalizado', 1)->exists();
    }
}
