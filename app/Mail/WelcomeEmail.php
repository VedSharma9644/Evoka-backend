<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WelcomeEmail extends Mailable
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Welcome to Our Application!',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.welcome',
            with: ['user' => $this->user]
        );
    }
}