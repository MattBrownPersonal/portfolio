<?php

namespace Tests\Browser\FrontEnd;

use App\Models\Deceased;
use App\Models\EmailVerification;
use App\Models\QueueEmail;
use App\Models\Site;
use App\Models\SiteStyles;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Assert;

class VM2267VerfiyEmailTest extends FrontEndTestCase
{
    const PATH = '/verifyaddress/';

    const PAGETITLE = 'Verifying Your Email Address';

    const ERRORMESSAGE = 'Could not be verified';

    const VERIFIEDMESSAGE = 'Verified';

    const INVALIDTOKEN = 'INVALIDTOKEN';

    const VALIDTOKEN = 'VALIDTOKEN_TEST';

    /**
     * Invalid email token results in error message
     *
     * @group vm2-267
     *
     * @return void
     **/
    public function testInvalidEmailTokenResultsInError()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            SiteStyles::factory()->withSiteId($site)->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $token = self::INVALIDTOKEN;
            $browser->visit($this->customerUrl($link.self::PATH.$token))
                ->waitForText(self::PAGETITLE)
                ->waitForText(self::ERRORMESSAGE)
                ->assertSee(self::ERRORMESSAGE);
        });
    }

    /**
     * Valid email token results in verification message
     *
     * @group vm2-267
     *
     * @return void
     **/
    public function testValidEmailTokenResultsVerificationMessage()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            SiteStyles::factory()->withSiteId($site)->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $token = self::VALIDTOKEN;
            $emailVerification = EmailVerification::factory()->withEmailHash($token)->create();
            $browser->visit($this->customerUrl($link.self::PATH.$token))
                ->waitForText(self::PAGETITLE)
                ->waitForText(self::VERIFIEDMESSAGE)
                ->assertSee(self::VERIFIEDMESSAGE);
            $this->assertNoErrorsInConsole($browser);
            // clean up
            $emailVerification->delete();
        });
    }

    /**
     * Valid email token results in verification message for already verified address
     *
     * @group vm2-267
     *
     * @return void
     **/
    public function testValidEmailTokenResultsVerificationMessageForAlreadyVerifiedAddress()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            SiteStyles::factory()->withSiteId($site)->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $token = self::VALIDTOKEN;
            $emailVerification = EmailVerification::factory()->withEmailHash($token)->asVerified()->create();
            $browser->visit($this->customerUrl($link.self::PATH.$token))
                ->waitForText(self::PAGETITLE)
                ->waitForText(self::VERIFIEDMESSAGE)
                ->assertSee(self::VERIFIEDMESSAGE);
            $this->assertNoErrorsInConsole($browser);
            // clean up
            $emailVerification->delete();
        });
    }

    /**
     * Valid email token results in verified email record
     *
     * @group vm2-267
     *
     * @return void
     **/
    public function testValidEmailTokenResultsInVerifiedEmailRecord()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            SiteStyles::factory()->withSiteId($site)->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $token = self::VALIDTOKEN;
            $emailVerification = EmailVerification::factory()->withEmailHash($token)->create();
            $browser->visit($this->customerUrl($link.self::PATH.$token))
                ->waitForText(self::PAGETITLE)
                ->waitForText(self::VERIFIEDMESSAGE)
                ->assertSee(self::VERIFIEDMESSAGE);
            $this->assertNoErrorsInConsole($browser);
            $emailVerifications = EmailVerification::where('verified', true);
            Assert::assertTrue($emailVerifications->count() == 1);
            // clean up
            $emailVerification->delete();
        });
    }

    /**
     * Valid email token results in queued emails being sent
     *
     * @group vm2-267
     *
     * @return void
     **/
    public function testValidEmailTokenResultsInQueuedEmailSent()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            SiteStyles::factory()->withSiteId($site)->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $token = self::VALIDTOKEN;
            $emailVerification = EmailVerification::factory()->withEmailHash($token)->create();
            $queuedEmail = QueueEmail::factory()->withEmailVerification($emailVerification)->withDeceased($deceased)->create();
            $browser->visit($this->customerUrl($link.self::PATH.$token))
                ->waitForText(self::PAGETITLE)
                ->waitForText(self::VERIFIEDMESSAGE)
                ->assertSee(self::VERIFIEDMESSAGE);
            $this->assertNoErrorsInConsole($browser);
            $emailVerifications = EmailVerification::where('verified', true);
            Assert::assertTrue($emailVerifications->count() == 1);
            $queuedEmails = QueueEmail::where('sent', true);
            Assert::assertTrue($queuedEmails->count() == 1);
            // clean up
            $queuedEmail->delete();
            $emailVerification->delete();
        });
    }
}
