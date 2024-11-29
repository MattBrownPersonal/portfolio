<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\SiteStyles;
use Illuminate\Database\Seeder;

class SitestylesSeeder extends Seeder
{
    /**
     * Run the database seeding for site_styles.
     *
     * @return void
     */
    public function run()
    {
        Site::all()->each(function ($site) {
            // set all sites to monochrome
            SiteStyles::create([
                'site_id' => $site->id,
                'primary_colour' => '#fff',
                'secondary_colour' => '#999',
                'primary_font_colour' => '#000',
                'secondary_font_colour' => '#555',
            ]);
        });
    }
}
