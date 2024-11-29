<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleSite;
use App\Models\Deceased;
use App\Models\Image;
use App\Models\Site;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Models\Article
     */
    public function index()
    {
        return Article::all();
    }

    /**
     * getSiteArticles
     *
     * @param int $id
     *
     * @return App\Models\Article
     */
    public function getSiteArticles($id)
    {
        return Article::where('site_id', $id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        Image::updateArticle($request, null);
    }

    /**
     * storeSiteArticles
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function storeSiteArticles(Request $request)
    {
        $lastArticle = ArticleSite::where('site_id', $request->site)->orderBy('order_number', 'desc')->first();
        if (! $lastArticle) {
            $nextOrderNumber = 1;
        } else {
            $nextOrderNumber = ($lastArticle->order_number + 1);
        }

        $siteArticle = new ArticleSite;
        $siteArticle->article_id = $request->article;
        $siteArticle->order_number = $nextOrderNumber;
        $siteArticle->site_id = $request->site;
        $siteArticle->save();

        return response()->json(['message' => 'Article Added Successfully'], 200);
    }

    /**
     * fetchSiteArticles
     *
     * @param int $code
     *
     * @return App\Models\ArticleSite
     */
    public function fetchSiteArticles($code)
    {
        if (is_numeric($code)) {
            $siteId = Deceased::where('code', $code)->first()->site_id;
        } else {
            $siteId = Site::where('name', str_replace('-', ' ', $code))->first()->id;
        }

        return ArticleSite::where('site_id', $siteId)->with('article')->orderBy('order_number')->get();
    }

    /**
     * fetchSiteArticle
     *
     * @param int $id
     *
     * @return App\Models\ArticleSite
     */
    public function show($id)
    {
        return Article::where('id', $id)->first();
    }

    /**
     * fetchSiteArticle
     *
     * @param int $id
     *
     * @return App\Models\ArticleSite
     */
    public function fetchSiteArticle($id)
    {
        return Article::where('id', $id)->first();
    }

    /**
     * adminFetchSiteArticles
     *
     * @param int $code
     *
     * @return App\Models\ArticleSite
     */
    public function adminFetchSiteArticles($code)
    {
        return ArticleSite::where('site_id', $code)->with('article')->orderBy('order_number')->get();
    }

    /**
     * storeNewOrder
     *
     * @param Illuminate\Http\Request $request
     * @param int $code
     *
     * @return Illuminate\Http\Response
     */
    public function storeNewOrder(Request $request, $id)
    {
        foreach (json_decode($request->articles) as $newArticle) {
            $article = ArticleSite::where('site_id', $newArticle->site_id)->where('article_id', $newArticle->article_id)->first();
            $article->order_number = $newArticle->order_number;
            $article->site_id = $newArticle->site_id;
            $article->article_id = $newArticle->article_id;
            $article->save();
        }

        return response()->json(['message' => 'Article Updated Successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        Image::updateArticle($request, $id);
    }
}
