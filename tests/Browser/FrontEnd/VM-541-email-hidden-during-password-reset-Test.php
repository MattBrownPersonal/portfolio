<?php

namespace Tests\Browser\FrontEnd;

use Laravel\Dusk\Browser;

/**
 * Email address should be URL encoded
 */
class VM541Test extends FrontEndTestCase
{
    /**
     * Email field should be hidden when user is resetting password
     *
     * @group VM-541
     *
     * @return void
     **/
    public function testEmailFieldIsHidden()
    {
        $this->browse(function (Browser $browser) {
            // Credentials used to test the reset password component
            $testEmail = 'admin%40admin.com';
            // Password reset token generated for email
            $testToken = '76b19336848578045e0a6ced6f7132ec1470f486a051b2174eabcfc8a1c919d7';
            $browser->visit($this->adminUrl("/password/reset/{$testToken}?email={$testEmail}/"))
            ->assertAttribute('#email', 'type', 'hidden')
            ->assertAttribute('input[name="token"]', 'type', 'hidden');
        });
    }

    /**
     * Email address should be URL encoded for spaces
     *
     * @group VM-541
     *
     * @return void
     **/
    public function testEmailAddressIsURLEncodedForSpaces()
    {
        $this->browse(function (Browser $browser) {
            // Credentials used to test the reset password component
            $testEmail = 'admin space test%40admin.com';
            $targetEmail = 'admin+space+test@admin.com';
            // Password reset token generated for email
            $testToken = '76b19336848578045e0a6ced6f7132ec1470f486a051b2174eabcfc8a1c919d7';
            $browser->visit($this->adminUrl("/password/reset/{$testToken}?email={$testEmail}"))
            ->assertAttribute('#email', 'value', $targetEmail);
        });
    }

    /**
     * Email address should be URL encoded for space encoding
     *
     * @group VM-541
     *
     * @return void
     **/
    public function testEmailAddressIsURLEncodedForSpaceEncoding()
    {
        $this->browse(function (Browser $browser) {
            // Credentials used to test the reset password component
            $testEmail = 'admin%20space%20test%40admin.com';
            $targetEmail = 'admin+space+test@admin.com';
            // Password reset token generated for email
            $testToken = '76b19336848578045e0a6ced6f7132ec1470f486a051b2174eabcfc8a1c919d7';
            $browser->visit($this->adminUrl("/password/reset/{$testToken}?email={$testEmail}"))
            ->assertAttribute('#email', 'value', $targetEmail);
        });
    }
}
