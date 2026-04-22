<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $emailData;
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email=$this->view('emails.emails')
        ->subject($this->emailData['subject'])
       ->with([
        "subject"=>$this->emailData['subject'],
        "g_date"=>$this->emailData['generated_date'],
        "d_date"=>$this->emailData['due_date'],
        "t_subject"=>$this->emailData['task_subject'],
        "s_name"=>$this->emailData['school_name'],
        "address"=>$this->emailData['address'],
        "city"=>$this->emailData['city'],
        "state"=>$this->emailData['state'],
        "c_number"=>$this->emailData['Contact_number'],
        "email"=>$this->emailData['email_id'],
        "link"=>$this->emailData['link'],
        //"msg"=>$this->emailData['forMSG'],
       ]);
       return $email;
    }
}
