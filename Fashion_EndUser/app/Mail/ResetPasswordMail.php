<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url; // URL chứa liên kết đặt lại mật khẩu

    public function __construct($url)
    {
        $this->url = $url;
    }
    // tiêu đề
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận đặt lại mật khẩu',
        );
    }
    // nội dung
    public function content(): Content
    {
        return new Content(
            view: 'auth.ResetPasswordMailTemplate',
        );
    }
    // các tập tin đính kèm
    public function attachments(): array
    {
        return [];
    }
}
