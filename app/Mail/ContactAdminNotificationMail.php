<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAdminNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【お問い合わせ】'.$this->contactMessage->name.'様より',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact.admin-notification',
            with: [
                'contactMessage' => $this->contactMessage,
            ],
        );
    }
}
