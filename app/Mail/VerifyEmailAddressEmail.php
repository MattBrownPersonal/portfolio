<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailAddressEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $tcandcsLink;

    public $verifyLink;

    public $deceasedLink;

    public $memorialSite;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($deceased, $siteModel, $email)
    {
        if ($deceased) {
            $baseDeceasedLink = $deceased->shopRoute($deceased->code, $deceased->firstname, $deceased->lastname);
        } else {
            $baseDeceasedLink = $siteModel->cremOnlyRoute($siteModel['slug']);
        }
        $hash = hash('sha256', $email);
        $this->tcandcsLink = $baseDeceasedLink.'/termsofservice';
        $this->verifyLink = $baseDeceasedLink.'/verifyaddress/'.$hash;
        $this->deceasedLink = $baseDeceasedLink;
        $this->memorialSite = $siteModel->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.visitor.verifyEmail')
            ->subject('Verifiy your email address : '.$this->memorialSite)
            ->with([
                'tcandcsLink' => $this->tcandcsLink,
                'verifyLink' => $this->verifyLink,
                'deceasedLink' => $this->deceasedLink,
                'memorialSite' => $this->memorialSite,
            ]);
    }
}
