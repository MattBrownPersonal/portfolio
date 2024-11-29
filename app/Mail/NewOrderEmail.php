<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;

    public $cartItems;

    public function __construct($name, $cartItems)
    {
        $this->name = $name;
        $this->cartItems = $cartItems;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.orders.newOrder')
                    ->subject('New Order Has Arrived For '.$this->cartItems['site_name'])
                    ->with([
                        'name' => $this->name,
                        'cartItems' => $this->cartItems,
                    ]);
    }
}
