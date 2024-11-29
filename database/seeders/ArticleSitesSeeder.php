<?php

namespace Database\Seeders;

use App\Models\ArticleSite;
use Illuminate\Database\Seeder;

class ArticleSitesSeeder extends Seeder
{
    /**
     * Run the database seeding article_sites table.
     *
     * @return void
     */
    public function run()
    {
        $westerlySiteId = 145;
        $numberOfArticles = 4;

        for ($i = 1; $i <= $numberOfArticles; $i++) {
            ArticleSite::create([
                'article_id' => $i,
                'order_number' => $i,
                'site_id' => $westerlySiteId,
            ]);
        }
    }
}
