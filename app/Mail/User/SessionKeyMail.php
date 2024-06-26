<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SessionKeyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email_code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email_code)
    {
        //
        $this->email_code = $email_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.user.session_key_mail');
    }
}
