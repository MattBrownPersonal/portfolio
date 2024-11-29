<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerOrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $request;

    public $site;

    public $orderNumber;

    public function __construct($request, $site, $orderNumber)
    {
        $this->request = $request;
        $this->site = $site;
        $this->orderNumber = $orderNumber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.applicant.customerOrderConfirmation')
                    ->subject('Thank you for your order')
                    ->with([
                        'request' => $this->request,
                        'site' => $this->site,
                        'orderNumber' => $this->orderNumber,
                    ]);
    }
}
