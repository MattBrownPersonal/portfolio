<?php

namespace Tests\Unit;

use App\Models\Deceased;
use App\Models\EmailVerification;
use App\Models\Product;
use App\Models\QueueEmail;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    const TESTEMAIL = 'test@example.com';

    /**
     * test that QueueEmail is created for unknown email address
     *
     * @group vm2-267
     */
    public function testThatQueueEmailIsCreatedForUnknownEmailAddress()
    {
        $request = $this->getRequest();
        $queuedEmails = QueueEmail::where('sent', false);
        $this->assertTrue($queuedEmails->count() == 0);
        $this->call('POST', 'api/sendDesigns', $request);
        $queuedEmails = QueueEmail::where('sent', false);
        $this->assertTrue($queuedEmails->count() == 1);
    }

    /**
     * test that EmailVerification is created for unknown email address
     *
     * @group vm2-267
     */
    public function testThatEmailVerificationIsCreatedForUnknownEmailAddress()
    {
        $request = $this->getRequest();
        $emailVerification = EmailVerification::where('verified', false);
        $this->assertTrue($emailVerification->count() == 0);
        $this->call('POST', 'api/sendDesigns', $request);
        $emailVerification = EmailVerification::where('verified', false);
        $this->assertTrue($emailVerification->count() == 1);
    }

    /**
     * test that QueueEmail is created for unverified email address
     *
     * @group vm2-267
     */
    public function testThatQueueEmailIsCreatedForUnverifiedEmailAddress()
    {
        EmailVerification::factory()->create();
        $request = $this->getRequest();
        $queuedEmails = QueueEmail::where('sent', false);
        $this->assertTrue($queuedEmails->count() == 0);
        $this->call('POST', 'api/sendDesigns', $request);
        $queuedEmails = QueueEmail::where('sent', false);
        $this->assertTrue($queuedEmails->count() == 1);
    }

    /**
     * test that EmailVerification is created for unverified email address
     *
     * @group vm2-267
     */
    public function testThatEmailVerificationIsCreatedForUnverifiedEmailAddress()
    {
        EmailVerification::factory()->withEmail(self::TESTEMAIL)->create();
        $request = $this->getRequest();
        $emailVerification = EmailVerification::where('verified', false);
        $this->assertTrue($emailVerification->count() == 1);
        $this->call('POST', 'api/sendDesigns', $request);
        $emailVerification = EmailVerification::where('verified', false);
        $this->assertTrue($emailVerification->count() == 1);
    }

    /**
     * test that verified email does not create queued emails
     *
     * @group vm2-267
     */
    public function testThatVerifiedEmailAddressDoesNotCreateQueuedEmails()
    {
        EmailVerification::factory()->withEmail(self::TESTEMAIL)->asVerified()->create();
        $request = $this->getRequest();
        $emailVerification = EmailVerification::where('verified', true);
        $this->assertTrue($emailVerification->count() == 1);
        $this->call('POST', 'api/sendDesigns', $request);
        $emailVerification = EmailVerification::where('verified', false);
        $this->assertTrue($emailVerification->count() == 0);
        $queuedEmails = QueueEmail::where('sent', false);
        $this->assertTrue($queuedEmails->count() == 0);
    }

    // test data
    private function getRequest()
    {
        $deceased = Deceased::factory()->create();
        $product = Product::factory()->create();
        $site = Site::factory()->create();
        $emailMessage = 'TEST';
        $recipient = self::TESTEMAIL;
        $chosenProductSpecs = '';
        $editedImage = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAABhGlDQ1BJQ0MgcHJvZmlsZQAAKJF9kT1Iw0AcxV9bpSIVFTuIOGSoThZERTtqFYpQIdQKrTqYXPoFTRqSFBdHwbXg4Mdi1cHFWVcHV0EQ/ABxdXFSdJES/5cUWsR4cNyPd/ced+8Af73MVLNjHFA1y0gl4kImuyoEXxFAH/oRw4zETH1OFJPwHF/38PH1LsqzvM/9OXqUnMkAn0A8y3TDIt4gnt60dM77xGFWlBTic+Ixgy5I/Mh12eU3zgWH/TwzbKRT88RhYqHQxnIbs6KhEk8RRxRVo3x/xmWF8xZntVxlzXvyF4Zy2soy12kOI4FFLEGEABlVlFCGhSitGikmUrQf9/APOX6RXDK5SmDkWEAFKiTHD/4Hv7s185MTblIoDnS+2PbHCBDcBRo12/4+tu3GCRB4Bq60lr9SB2KfpNdaWuQI6N0GLq5bmrwHXO4Ag0+6ZEiOFKDpz+eB9zP6piwwcAt0r7m9Nfdx+gCkqavkDXBwCIwWKHvd491d7b39e6bZ3w/gp3LTqY/OYwAAAAlwSFlzAAAuIwAALiMBeKU/dgAAAAd0SU1FB+cDEBIAB1HqTmgAAAAZdEVYdENvbW1lbnQAQ3JlYXRlZCB3aXRoIEdJTVBXgQ4XAAAADElEQVQI12NgYGAAAAAEAAEnNCcKAAAAAElFTkSuQmCC';
        $totalPrice = '999.99';
        $customerEmail = self::TESTEMAIL;
        $url = 'https://example.com';
        $shareWithCrem = false;
        $lines = $this->getLines();
        $chosenProductSpecs = $this->getChosenProductSpecs();
        $payload = [
            'testMode' => true,
            'message' => $emailMessage,
            'recipient' =>  $recipient,
            'chosenProductSpecs' => json_encode($chosenProductSpecs),
            'product' => json_encode($product),
            'lines' => json_encode($lines),
            'editedImage'=>  $editedImage,
            'totalPrice' => $totalPrice,
            'customerEmail' => $customerEmail,
            'url' => $url,
            'id' => $deceased->id,
            'site_id' => $site->id,
            'shareWithCrem' => $shareWithCrem,
        ];

        return $payload;
    }

    // test data
    private function getLines()
    {
        $lines = [
            [
                'letterCount' => 25,
                'text' =>'Hollie Test Smith',
                'type' =>'fullname',
                'order_index' => 1,
            ], [
                'letterCount' => 25,
                'text' =>'02/11/2022',
                'type' =>'date of death',
                'order_index' => 2,
            ], [
                'letterCount' => 25,
                'text' =>'In our hearts',
                'type' =>'custom line 3',
                'order_index' => 3,
            ], ];

        return $lines;
    }

    // test data
    private function getChosenProductSpecs()
    {
        $chosenProductSpecs = [
            'textColour' => [
                'id' => null,
                'name' => 'Silver',
                'price' => null,
                'type_name' => 'Text colour',
            ],
            'material' => [
                'id' => null,
                'name' => 'Granite',
                'price' => 0,
                'type_name' => 'Material',
            ], ];

        return $chosenProductSpecs;
    }
}
