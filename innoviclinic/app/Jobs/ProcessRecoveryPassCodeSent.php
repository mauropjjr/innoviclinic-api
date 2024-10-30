<?php

namespace App\Jobs;

use App\Mail\RecoveryPassCodeSent;
use App\Models\Otp;
use App\Models\Pessoa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessRecoveryPassCodeSent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    /**
     * Create a new job instance.
     */
    public function __construct(
        public Otp $otp,
        public Pessoa $user
    )
    {
        $this->otp = $otp;
        $this->$user = $user;
        $this->onQueue(env('QUEUE_EMAILS', 'emails'));
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user)->send(new RecoveryPassCodeSent($this->otp));
    }
}
