<?php

namespace App\Mail;

use App\Models\Settings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomProductDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $customImageDetails;

    public $url;

    public $sitename;

    public $product;

    public $editedImage;

    public $totalPrice;

    public $custommessage;

    public $bccAddress;

    public $replyToAddress;

    public $replyToName;

    public $shareWithCrem;

    public function __construct($customImageDetails, $url, $sitename, $product, $editedImage, $totalPrice, $custommessage, $replyToAddress, $replyToName, $bccAddress, $shareWithCrem)
    {
        $this->customImageDetails = $customImageDetails;
        $this->url = $url;
        $this->sitename = $sitename;
        $this->product = $product;
        $this->editedImage = $editedImage;
        $this->totalPrice = $totalPrice;
        $this->custommessage = $custommessage;
        $this->replyToAddress = $replyToAddress;
        $this->replyToName = $replyToName;
        $this->bccAddress = $bccAddress;
        $this->shareWithCrem = $shareWithCrem;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = 'emails.customproduct.customProductDetails';
        if ($this->shareWithCrem == 'true') {
            $template = 'emails.customproduct.customProductDetailsCrem';
        }

        $data = [
            'url' => $this->url,
            'sitename' => $this->sitename,
            'greeting' => Settings::getSetting('email_greeting'),
            'customImageDetails' => $this->customImageDetails,
            'product' => $this->product,
            'editedImage' => $this->editedImage,
            'totalPrice' => $this->totalPrice,
        ];
        if ($this->custommessage) {
            $data['custommessage'] = $this->custommessage;
        }

        $mailable = $this->view($template)
            ->with($data)
            ->subject('Custom Product Details');

        if ($this->bccAddress) {
            $mailable->bcc($this->bccAddress);
        }
        if ($this->replyToAddress && $this->replyToName) {
            $mailable->replyTo($this->replyToAddress, $this->replyToName);
        }

        return $mailable;
    }
}
