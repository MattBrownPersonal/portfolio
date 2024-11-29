<?php

namespace App\Mail;

use App\Models\Settings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicantCodeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;

    public $url;

    public $deceasedName;

    public $site;

    public $bccAddress;

    public function __construct($deceasedName, $site, $name, $url, $bccAddress)
    {
        $this->name = $name;
        $this->url = $url;
        $this->deceasedName = $deceasedName;
        $this->site = $site;
        $this->bccAddress = $bccAddress;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailable = $this->markdown('emails.applicant.codeemail')
        ->subject('Supporting you following your bereavement')
        ->with([
            'url' => $this->url,
            'name' => $this->name,
            'content' => Settings::getSetting('email_content'),
            'deceased' => $this->deceasedName,
            'site' => $this->site,
        ]);
        if ($this->bccAddress) {
            $mailable->bcc($this->bccAddress);
        }

        return $mailable;
    }
}
