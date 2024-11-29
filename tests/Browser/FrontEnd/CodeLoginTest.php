<?php

namespace Tests\Browser\FrontEnd;

use App\Models\Deceased;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CodeLoginTest extends DuskTestCase
{
    /**
     * Login fails with invalid code
     *
     * @return void
     */
    public function testLoginFailsWithInvalidCode()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->customerUrl('/'))
                    ->assertSee('Please enter your privacy key')
                    ->type('#input-9', 'TEST123')
                    ->click('button[type=submit]')
                    ->waitForText('Sorry, there are no results for TEST123');
        });
    }

    /**
     * Login successful with valid code
     *
     * @return void
     */
    public function testLoginSuccessfulWithValidLandingCode()
    {
        $this->browse(function (Browser $browser) {
            $deceased = Deceased::factory()->create();
            $browser->visit($this->customerUrl('/'))
                    ->assertSee('Please enter your privacy key')
                    ->type('#input-9', $deceased->landing_code)
                    ->click('button[type=submit]')
                    ->waitForText('Bereavement support');

            // Check URL is in the correct format
            $expectedUrlSuffix = ':8080/m/'.$deceased->code.'-'.$deceased->firstname.'-'.$deceased->lastname;
            $this->assertStringContainsString($expectedUrlSuffix, $browser->driver->getCurrentURL());
        });
    }
}
