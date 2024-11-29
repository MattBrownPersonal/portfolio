<?php

namespace Tests\Browser\FrontEnd;

use App\Models\Article;
use App\Models\ArticleSite;
use App\Models\Deceased;
use App\Models\Product;
use App\Models\Site;
use App\Models\SiteStyles;
use Laravel\Dusk\Browser;

class BereavementSupportTest extends FrontEndTestCase
{
    /**
     * Test articles are listed
     *
     * @group vm642a
     * @return void
     */
    public function testArticlesListed()
    {
        $this->browse(function (Browser $browser) {
            $menuSelector = '.middle-nav-link';
            $supportLinkSelector = '.middle-nav-link';
            $site = Site::factory()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $products = Product::factory()->withSite($site)->withFeaturedProduct()->create();
            $articles = Article::factory()->count(5)->create();
            foreach ($articles as $article) {
                $articleSites = ArticleSite::factory()
                                ->withSite($site)
                                ->withArticle($article)
                                ->create();
            }
            $browser->visit($this->customerUrl($link))
                    ->waitFor($menuSelector)
                    ->assertSeeIn($supportLinkSelector, 'Bereavement Support')
                    ->click($supportLinkSelector)
                    ->waitFor('.bereavementArticle');
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * Test article is shown
     *
     * @group vm642b
     * @return void
     */
    public function testArticleShown()
    {
        $this->browse(function (Browser $browser) {
            $menuSelector = '.middle-nav-link';
            $supportLinkSelector = '.middle-nav-link';
            $articleLinkSelector = '.bereavementArticle a';
            $articleLoadedSelector = '.articleHeader h1';

            $site = Site::factory()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $products = Product::factory()->withSite($site)->withFeaturedProduct()->create();
            $articles = Article::factory()->count(5)->create();
            foreach ($articles as $article) {
                $articleSites = ArticleSite::factory()
                                ->withSite($site)
                                ->withArticle($article)
                                ->create();
            }
            $browser->visit($this->customerUrl($link.$this->getArticlePath($articles[0])))
                    ->waitFor($menuSelector)
                    ->assertSeeIn($supportLinkSelector, 'Bereavement Support')
                    ->click($supportLinkSelector)
                    ->waitFor('.bereavementArticle');
            $this->assertNoErrorsInConsole($browser);
        });
    }
}
