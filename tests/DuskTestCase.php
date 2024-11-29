<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;
    use MigrateFreshSeedOnce;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments(collect([
            '--window-size=1920,1080',
        ])->unless($this->hasHeadlessDisabled(), function ($items) {
            return $items->merge([
                '--disable-gpu',
                '--headless',
                '--no-sandbox',
                '--disable-dev-shm-usage',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    protected function adminUrl($path)
    {
        return 'http://admin.localhost:8080'.$path;
    }

    protected function customerUrl($path)
    {
        return 'http://localhost:8080'.$path;
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     *
     * @return bool
     */
    protected function hasHeadlessDisabled()
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
               isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    /**
     * On failing test
     * store the DOM at tests\Browser\source
     * store the console at tests\Browser\console
     */
    protected function captureFailuresFor($browsers)
    {
        parent::captureFailuresFor($browsers);

        $browsers->each(function ($browser, $key) {
            $browser->storeSource('failure-'.$this->getCallerName().'-'.$key);
            $browser->storeConsoleLog('failure-'.$this->getCallerName().'-'.$key);
        });
    }

    protected function assertNoErrorsInConsole($browser)
    {
        $consoleLog = $browser->driver->manage()->getLog('browser');
        $unsafeConsoleLog = array_filter(
            $consoleLog,
            function ($value) {
                return $value['message'] != 'http://localhost:8000/api/event - Failed to load resource: net::ERR_CONNECTION_REFUSED';
            }
        );
        if (count($unsafeConsoleLog) > 0) {
            echo var_dump($unsafeConsoleLog);
        }
        $this->assertEmpty($unsafeConsoleLog);
    }
}
