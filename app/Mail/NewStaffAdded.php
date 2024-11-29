<?php

namespace App\Mail;

use App\Models\Settings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewStaffAdded extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;

    public $email;

    public $token;

    public $subject;

    public $body;

    public $appUrl;

    public function __construct($name, $email, $token, $appUrl)
    {
        $this->name = $name;
        $this->email = $email;
        $this->token = $token;
        $this->url = $appUrl.'password/reset/'.$token.'?email='.$email;
        $this->subject = Settings::getSetting('new_vivedia_staff_email_subject_text');
        $this->body = Settings::getSetting('new_vivedia_staff_email_body');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.staff.new')
                    ->subject($this->subject)
                    ->with([
                        'url' => $this->url,
                        'body' => $this->body,
                    ]);
    }
}
