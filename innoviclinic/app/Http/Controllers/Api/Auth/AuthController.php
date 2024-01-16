<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use App\Models\Pessoa;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function auth(AuthRequest $request)
    {
        $user = Pessoa::where('email', $request->email)->first();

         if (!$user || !Hash::check($request->senha, $user->senha)) {
             throw ValidationException::withMessages([
                 'email' => ['As credenciais fornecidas estão incorretas']
             ]);
         }

        // Logout others devices
        // if ($request->has('logout_others_devices'))
        $user->tokens()->delete();

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->pessoa()->tokens()->delete();

        return response()->json([
            'message' => 'success',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->pessoa();

        return response()->json([
            'me' => $user,
        ]);
    }
    public function novo(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'celular' => ['required', 'string', 'max:12'],

            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Pessoa::class],
            //    'password' => ['required', 'confirmed', Password::defaults()],
            'senha' => ['required', Password::defaults()],
        ]);

        $user = Pessoa::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'tipo_usuario' => $request->tipo_usuario,
            'celular' => $request->celular,
            'senha' => Hash::make($request->senha),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->json($user);
    }
}
