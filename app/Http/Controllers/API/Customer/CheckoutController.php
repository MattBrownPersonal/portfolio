<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Mail\CustomProductDetails;
use App\Mail\VerifyEmailAddressEmail;
use App\Models\Checkout;
use App\Models\Deceased;
use App\Models\EmailVerification;
use App\Models\Order;
use App\Models\QueueEmail;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    const FAKE_SITE_EMAIL = 'is_test@example.com';

    /**
     * getAppUrl
     *
     * @return string
     */
    public static function getAppUrl()
    {
        return response()->json(['message' => 'Error: Could not get app url', 'url' => config('app.app_url')]);
    }

    /**
     * getAdminAppUrl
     *
     * @return string
     */
    public static function getAdminAppUrl()
    {
        return config('app.admin_app_url');
    }

    public function getStripeKey()
    {
        return config('app.stripe_publishable');
    }

    /**
     * sendPayment
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return App\Models\Checkout
     */
    public function sendPayment(Request $request)
    {
        return Checkout::createPaymentIntent($request);
    }

    /**
     * sendEmail
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return bool
     */
    public function sendEmail(Request $request)
    {
        $order = Order::saveOrder($request, Order::ENQUIRYFROMSHARE);
        $orderItem = Order::saveOrderItemFromVenueContact($request, $order);

        $replyToAddress = $request->customerEmail;
        $replyToName = '';
        $toAddress = $request->recipient;
        $site = Site::find($request->site_id);
        if ($request->shareWithCrem == 'true') {
            $bccAddress = env('MAIL_BCC_ADDRESS', 'ENV_SECRET_NOT_SETUP');
            $replyToName = 'Enquirer';
            $toAddress = $site->primary_contact_email;
        } else {
            $replyToName = 'Your friend';
            $bccAddress = null;
        }

        try {
            $this->processEmail($replyToAddress, $request, $site, $replyToName, $bccAddress, $toAddress);
        } catch(\Exception $e) {
            return response()->json(['message' => 'Error: Could not send email',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function processEmail($replyToAddress, $request, $site, $replyToName, $bccAddress, $toAddress)
    {
        $emailVerification = EmailVerification::getEmailVerification($replyToAddress);

        $mail = new CustomProductDetails(
            $request->chosenProductSpecs,
            $request->url,
            $site->name,
            $request->product,
            $request->editedImage,
            $request->totalPrice,
            $request->message,
            $replyToAddress,
            $replyToName,
            $bccAddress,
            $request->shareWithCrem
        );
        if ($emailVerification && $emailVerification->verified) {
            if ((! $request->testMode) && ($toAddress !== self::FAKE_SITE_EMAIL)) {
                Mail::to($toAddress)->send($mail);
            }
        } else {
            // email not verified
            if (! $emailVerification) {
                $emailVerification = EmailVerification::newEmailVerification($replyToAddress);
            }
            // queue email
            $body = ''; // the way to capture the html body, but it will not contain attachments is ($mail)->render();
            $subject = ''; // subject will be generated when email is sent
            $emailJsonData = [
                'chosenProductSpecs' => $request->chosenProductSpecs,
                'url' => $request->url,
                'name' => $site->name,
                'product' => $request->product,
                'editedImage' => $request->editedImage,
                'totalPrice' => $request->totalPrice,
                'message' => $request->message,
                'replyToAddress' => $replyToAddress,
                'replyToName' => $replyToName,
                'bccAddress' => $bccAddress,
                'shareWithCrem' => $request->shareWithCrem,
            ];
            $emailJson = json_encode($emailJsonData);
            QueueEmail::queueEmail($toAddress, $emailVerification->id, $body, $subject, $request->id, 'custom-product-json', $emailJson);
            // send verfication
            $deceased = Deceased::where('id', $request->id)->first();
            $siteModel = Site::find($site->id);
            $this->sendVerficationEmail($deceased, $siteModel, $replyToAddress, $request->testMode);
        }
    }

    /**
     * sendVerificationEmail
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return bool
     */
    public function sendVerficationEmail($deceased, $siteModel, $email, $testMode)
    {
        if (! $testMode) {
            Mail::to($email)->send(new VerifyEmailAddressEmail($deceased, $siteModel, $email));
        }
    }
}
