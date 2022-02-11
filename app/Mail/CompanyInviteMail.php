<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompanyInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $companyId;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $invitationUrl = lurl('employers-invitation/'.$this->companyId.'/register');
        return $this->markdown('emails.companyInviteMembers')->subject("Ako Jobs Company Invitation")->with('invitationUrl',$invitationUrl);    }
    }
