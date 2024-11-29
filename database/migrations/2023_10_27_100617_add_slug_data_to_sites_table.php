<?php

use App\Models\Site;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugDataToSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $sites = Site::all();
            foreach ($sites as $site) {
                $find = ['.', ' ', '&'];
                $replace = ['-', '-', 'and'];
                $siteName = $site->name;

                $replaceFirstPass = str_replace($find, $replace, $siteName);

                $find = ['---', '--'];
                $replace = ['-', '-'];

                $site->slug = str_replace($find, $replace, $replaceFirstPass);
                $site->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            //
        });
    }
}
