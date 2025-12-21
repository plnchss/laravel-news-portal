<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class StatMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $countComment, public $articles)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Commentmail',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.stat',
            with: [
                'countComment' => $this->countComment,
                'countArticle' => $this->articles,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
