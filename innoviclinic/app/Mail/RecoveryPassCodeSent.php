<?php

namespace App\Mail;

use App\Models\Otp;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Support\Facades\Storage;

class RecoveryPassCodeSent extends Mailable
{
    use 
    SerializesModels;

    public Otp $otp;
    /**
     * Create a new message instance.
     */
    public function __construct(Otp $otp)
    {
        $this->otp = $otp;
        // $this->onQueue('default:emails');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Innoviclinic code :code', ['code' => $this->otp->code]),
        );
    }
    
    public function headers(): Headers
    {
        //X-Entity-Ref-ID pq o gmail tá bugando o html achando que a mensagem é uma conversa https://stackoverflow.com/questions/16689882/gmail-wraps-certain-html-elements-in-a-class-called-im
        return new Headers(
            text: [
                'X-Entity-Ref-ID' => 'force',
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.message',
            with: [
                "code" => $this->otp->code,
                "otpImage" => $this->getOtpImage(env('OTP_EMAIL_IMAGE_NAME', 'otp.jpg'))
            ]
        );
    }

    public function getOtpImage(string $fileName)
    {
        return asset('storage/public/'.$fileName);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
