<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::factory()
            ->count(20)
            ->create();

        $sites = Site::all();

        Supplier::all()->each(function ($supplier) use ($sites) {
            $supplier->sites()->attach(
                $sites->random(rand(1, 2))->pluck('id')->toArray()
            );
        });
    }
}
