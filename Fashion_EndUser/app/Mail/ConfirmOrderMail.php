<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmOrderMail extends Mailable
{
    use Queueable, SerializesModels;
    public $order;
    public $user;

    public function __construct($order, $user)
    {
        $this->order = $order;
        $this->user = $user;
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận đơn đặt hàng',
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'mail.order-verify',
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

    public function build()
    {
        return $this->view('mail.order-verify')
            ->with([
                'order' => $this->order,
                'user' => $this->user,
            ])->subject('Xác nhận đơn hàng');
    }

}
