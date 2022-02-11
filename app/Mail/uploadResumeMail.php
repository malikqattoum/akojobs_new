<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class uploadResumeMail extends Mailable
{
    use Queueable, SerializesModels;
    public $emailLang;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($emailLang = "en")
    {
        $this->emailLang = $emailLang; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->emailLang == "ar")
            return $this->markdown('emails.uploadResumeAr');
        else
            return $this->markdown('emails.uploadResume');
    }
}
