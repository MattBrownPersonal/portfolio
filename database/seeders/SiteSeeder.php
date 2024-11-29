<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = fopen(base_path().'/public/uploads/newsites.csv', 'r');
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
                Site::create([
                    'obitus_site_id'  => $importData[0],
                    'name'  => $importData[1],
                    'account_type'  => $importData[2],
                    'site_type'  => $importData[3],
                    'operator_type'  => $importData[4],
                    'parent_account'  => $importData[5],
                    'primary_contact_name'  => $importData[6],
                    'primary_contact_email'  => $importData[7],
                    'phone'  => $importData[8],
                    'county'  => $importData[9],
                    'street'  => $importData[10],
                    'city'  => $importData[11],
                    'country'  => $importData[12],
                    'postcode'  => $importData[13],
                    'region'  => $importData[14],
                    'uses_categories' => 1,
                ]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
        }
    }
}
