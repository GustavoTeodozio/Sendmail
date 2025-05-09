<?php

namespace App\Mail;

use App\Models\Smtp;
use App\Models\SmtpSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Auth;

class Send extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $mensagem;

    /**
     * Cria uma nova instância de mensagem.
     *
     * @param string $subject
     * @param string $mensagem
     */
    public function __construct($subject, $mensagem)
    {
        $this->subject = $subject;
        $this->mensagem = $mensagem;
    }

    /**
     * Definir o envelope da mensagem (assunto e remetente).
     */
    public function envelope(): Envelope
    {
        
        $smtp = Smtp::where('user_id', Auth::id())->first();

        if ($smtp) {
     
            return new Envelope(
                subject: $this->subject, 
                from: new Address($smtp->from_address, $smtp->from_name), 
            );
        }

        return new Envelope(
            subject: $this->subject, 
            from: new Address($smtp->from_address, $smtp->from_name), 
        );
    }

    /**
     * Definir o conteúdo da mensagem.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.meuemail',  
            with: [
                'mensagem' => $this->mensagem,  
            ],
        );
    }

    /**
     * Definir os anexos da mensagem (caso haja).
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
