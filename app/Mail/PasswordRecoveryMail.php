<?php

// app/Mail/PasswordRecoveryMail.php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordRecoveryMail extends Mailable
{
    use SerializesModels;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function build()
    {
        return $this->view('emails.password-recovery')
                    ->with(['token' => $this->token]);
    }
}
