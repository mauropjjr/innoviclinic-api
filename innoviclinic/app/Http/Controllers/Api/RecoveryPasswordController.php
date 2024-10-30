<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OtpService;

class RecoveryPasswordController extends Controller
{
    protected OtpService $otpService;
    public function __construct() {
        $this->otpService = new OtpService();
    }
    public function otp(Request $request)
    {
        $this->otpService->sendOtp($request);
    }
}
