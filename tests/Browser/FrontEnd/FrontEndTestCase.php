<?php

namespace Tests\Browser\FrontEnd;

use App\Models\Deceased;
use Tests\DuskTestCase;

abstract class FrontEndTestCase extends DuskTestCase
{
    protected function selectDeceasedAs($browser, $deceased)
    {
        if ($deceased == null) {
            $deceased = Deceased::orderBy('id')->first();
        }
        $link = $this->getDeceasedLink($deceased);
        $browser->visit($this->customerUrl($link));

        return $deceased;
    }

    public function clearCustom(Browser $browser, $selector)
    {
        $value = $browser->value($selector);
        $browser->keys($selector, ...array_fill(0, strlen($value), '{backspace}'));
    }

    protected function getDeceasedLink($deceased)
    {
        if ($deceased == null) {
            $deceased = Deceased::orderBy('id')->first();
        }

        return '/m/'.$deceased->code.'-'.$deceased->firstname.'-'.$deceased->lastname;
    }

    protected function getProductPath($product)
    {
        // TODO handle categories - /catid/productpage/productid
        return '/productpage/'.$product->id;
    }

    protected function getDeceasedForSite($siteId)
    {
        return Deceased::where('site_id', $siteId)->orderBy('id')->first();
    }

    protected function getArticlePath($article)
    {
        return '/viewarticle/'.$article->id;
    }

    protected function getHomePath()
    {
        return '/';
    }

    // Same path as getCategoriesPath
    protected function getMemorialsPath()
    {
        return '/categories';
    }

    protected function getCategoriesPath()
    {
        return '/categories';
    }
}
