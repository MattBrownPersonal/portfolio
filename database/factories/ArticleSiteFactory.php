<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleSiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

        ];
    }

    /**
     * Specifiy site
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withSite($site)
    {
        return $this->state(function () use ($site) {
            return [
                'site_id' => $site->id,
            ];
        });
    }

    /**
     * Specifiy srticle ID
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withArticle($article)
    {
        return $this->state(function () use ($article) {
            return [
                'article_id' => $article->id,
            ];
        });
    }
}
