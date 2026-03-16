<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $title,
        public string $body,
        public array $data = [],
    )
    {
        //
    }

    public function build(): NotificationMail
    {
        return $this->subject($this->title)
            ->view('emails.notification')
            ->with([
                'title' => $this->title,
                'message' => $this->body,
                'data' => $this->data
            ]);
    }
}
