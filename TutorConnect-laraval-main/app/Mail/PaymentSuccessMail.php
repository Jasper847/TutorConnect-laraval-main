<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public ?Payment $payment;

    public function __construct(Booking $booking, ?Payment $payment = null)
    {
        $this->booking = $booking;
        $this->payment = $payment ?: $booking->payment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking & Payment Confirmation — #' . $this->booking->booking_code . ' [TutorConnect Demo]',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-success',
        );
    }
}
