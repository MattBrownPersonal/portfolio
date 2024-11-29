<?php

namespace Database\Seeders;

use App\Models\Memorialisations;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemorialisationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = fopen(base_path().'/public/uploads/memorialisations.csv', 'r');
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
                Memorialisations::create([
                    'name' => $importData[1],
                ]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
        }
    }
}
