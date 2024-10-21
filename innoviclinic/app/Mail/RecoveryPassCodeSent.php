<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Headers;

class RecoveryPassCodeSent extends Mailable
{
    use 
    SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        // $this->onQueue('default:emails');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recovery Pass Code Sent',
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
        );
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
