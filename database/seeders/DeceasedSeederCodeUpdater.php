<?php

namespace Database\Seeders;

use App\Models\Deceased;
use Illuminate\Database\Seeder;

class DeceasedSeederCodeUpdater extends Seeder
{
    /**
     * This will update all deceased with a new landing code.
     *
     * @return void
     */
    public function run()
    {
        $deceased = Deceased::all();
        foreach ($deceased as $person) {
            $code = Deceased::generateCode();
            $person->landing_code = $code;
            $person->save();
        }
    }
}
