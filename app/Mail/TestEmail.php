<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.test-email')
                   ->subject('Test Email')
                   ->with([
                       'message' => 'This is a test email to verify that your email configuration is working correctly.'
                   ]);
    }
}
