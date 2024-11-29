<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicantQueryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;

    public $email;

    public $contactNumber;

    public $message;

    public $bccAddress;

    public function __construct($name, $email, $contactNumber, $message, $bccAddress)
    {
        $this->name = $name;
        $this->email = $email;
        $this->contactNumber = $contactNumber;
        $this->message = $message;
        $this->bccAddress = $bccAddress;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailable = $this->markdown('emails.applicant.query')
                    ->from($this->email)
                    ->subject('New Customer Query')
                    ->with([
                        'name' => $this->name,
                        'query' => $this->message + ' : ' + $this->contactNumber,
                    ]);
        if ($this->bccAddress) {
            $mailable->bcc($this->bccAddress);
        }

        return $mailable;
    }
}
