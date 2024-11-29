<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerContact extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;

    public $number;

    public $message;

    public $customerEmail;

    public $bccAddress;

    public function __construct($name, $number, $customerEmail, $message, $bccAddress)
    {
        $this->name = $name;
        $this->number = $number;
        $this->message = $message;
        $this->customerEmail = $customerEmail;
        $this->bccAddress = $bccAddress;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailable = $this->markdown('emails.applicant.customercontact')
        ->subject('New Customer Query')
        ->with([
            'name' => $this->name,
            'number' => $this->number,
            'message' => $this->message,
            'customerEmail' => $this->customerEmail,
        ]);
        if ($this->customerEmail) {
            $mailable->replyTo($this->customerEmail, 'New Customer Query');
        }
        if ($this->bccAddress) {
            $mailable->bcc($this->bccAddress);
        }

        return $mailable;
    }
}
