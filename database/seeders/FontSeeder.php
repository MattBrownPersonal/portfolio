<?php

namespace Database\Seeders;

use App\Models\Font;
use Illuminate\Database\Seeder;

class FontSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fonts = [
            'Arial',
            'Arial Bold',
            'Blooming Elegant',
            'CG Triumvirate Bold',
            'Gill Sans',
            'ITC Zapt Chancery Bold',
            'Noyh',
            'Mason',
            'Seagull Md Bt',
            'Stylish Calligraphy',
            'Times New Roman',
            'Times New Roman Bold',
        ];

        foreach ($fonts as &$font) {
            Font::firstOrCreate(['name' => $font]);
        }
    }
}
