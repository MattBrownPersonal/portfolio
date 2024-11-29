<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = fopen(base_path().'/public/uploads/products.csv', 'r');
        $importData_arr = [];
        $rowIndex = 0;
        while (($filedata = fgetcsv($file, 1000, ',')) !== false) {
            $num = count($filedata);
            if ($rowIndex == 0) {
                $rowIndex++;
                continue;
            }
            for ($c = 0; $c < $num; $c++) {
                $importData_arr[$rowIndex][] = $filedata[$c];
            }
            $rowIndex++;
        }
        fclose($file);
        foreach ($importData_arr as $importData) {
            try {
                DB::beginTransaction();
                Product::create([
                    'supplier_id' => $importData[0],
                    'name' => $importData[1],
                    'description' => $importData[2],
                    'price' => $importData[3],
                    'site_id' => $importData[4],
                    'memorialisation_id' => $importData[5],
                    'featured' => $importData[6],
                    'saved' => $importData[7],
                    'sku' => $importData[8],
                ]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
}
