<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployerResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $employer;

    public function __construct($employer, $token)
    {
        $this->employer = $employer;
        $this->token = $token;
    }


    public function build()
    {
        return $this->subject('Your Password Reset Token')
                    ->view('emails.employer-reset')
                    ->with([
                        'name' => $this->employer->company_name,
                        'token' => $this->token,
                    ]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Employer Reset Password Mail',
        );
    }

    /**
     * Get the message content definition.
     */
   

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
