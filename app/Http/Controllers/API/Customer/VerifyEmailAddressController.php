<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Mail\CustomProductDetails;
use App\Mail\QueuedEmail;
use App\Models\EmailVerification;
use App\Models\QueueEmail;
use Illuminate\Support\Facades\Mail;

class VerifyEmailAddressController extends Controller
{
    public function verifyEmailAddress($token)
    {
        EmailVerification::verifyToken($token); // this will throw an exception if fails to verify
        $this->sendQueuedEmails(EmailVerification::where('email_hash', $token)->first(), $this->isTestToken($token));
    }

    // Avoid sending email during tests
    private function isTestToken($token)
    {
        return str_ends_with($token, '_TEST');
    }

    // TODO:: refactor this, emails should be sent via a background task
    private function sendQueuedEmails($emailVerification, $testMode)
    {
        // this function exists simple to show the email sending from the serialised db record can function
        $emails = QueueEmail::where('email_verification_id', $emailVerification->id)->where('sent', 0)->get();
        foreach ($emails as $email) {
            $this->sendQueuedEmail($email, $emailVerification, $testMode);
        }
    }

    // TODO:: refactor this, emails should be sent via a background task
    private function sendQueuedEmail($email, $emailVerification, $testMode)
    {
        if ($email->email_type == 'custom-product-json') {
            $dataJson = $email->email_json;
            $data = json_decode($dataJson, true);
            $mail = new CustomProductDetails(
                $data['chosenProductSpecs'],
                $data['url'],
                $data['name'],
                $data['product'],
                $data['editedImage'],
                $data['totalPrice'],
                $data['message'],
                $data['replyToAddress'],
                $data['replyToName'],
                $data['bccAddress'],
                $data['shareWithCrem']
            );
            Mail::to($email->to_email)
                ->send($mail);
        } else {
            $mailer = (new QueuedEmail());
            $mailer
                ->subject($email->email_subject)
                ->html($email->email_body);
            if (! $testMode) {
                Mail::to($emailVerification->email)
                    ->send($mailer);
            }
        }
        $email->sent = true;
        $email->save();

        return true;
    }
}
