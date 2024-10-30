<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Pessoa;
use App\Models\Otp;
use App\Jobs\ProcessRecoveryPassCodeSent;

class OtpService
{
    public function sendOtp(Request $request)

    {
        $request->validate(['email' => 'required|email|exists:pessoas,email']);
        $user = Pessoa::where("email", $request->email)->get(["id"])->toArray();
        $user = Pessoa::find($user[0]["id"]);
        $otp = Otp::create(["pessoa_id" => $user->id]);
    }
}
