<?php

use Illuminate\Database\Migrations\Migration;

class UpdateArticlesRemoveStyling extends Migration
{
    /**
     * Run the migrations.
     *
     * Regular expressions are used to remove and replace Word stylings
     *
     * @return void
     */
    public function up()
    {
        DB::statement('UPDATE articles SET article=REGEXP_REPLACE(article, \'style=["][^"]+["]\', \'\');');
        DB::statement('UPDATE articles SET article=REGEXP_REPLACE(article, \'(\n|\r)\', \' \');');
        DB::statement('UPDATE articles SET article=REGEXP_REPLACE(article, \' class=["](MsoNormal)["]\', \'\');');
        DB::statement('UPDATE articles SET article=REGEXP_REPLACE(article, \'<o:p></o:p>\', \'\');');
        DB::statement('UPDATE articles SET article=REGEXP_REPLACE(article, \'<span\\\\s?></span>\', \'\');');
        DB::statement('UPDATE articles SET article=REGEXP_REPLACE(article, \'<b\\\\s?></b>\', \'\');');
        DB::statement('UPDATE articles SET article=REGEXP_REPLACE(article, \'<p\\\\s?></p>\', \'\');');
        DB::statement('UPDATE articles SET article=REGEXP_REPLACE(article, \'(<p\\\\s?><b\\\\s?><span\\\\s?>)(.+?)(</span></b></p>)\', \'<h2 class="section-header">$2</h2>\');');
        DB::statement('UPDATE articles SET article=REGEXP_REPLACE(article, \'●\', \'<div class="bullet-list-item level-1"></div> \');');
        DB::statement('UPDATE articles SET article=REGEXP_REPLACE(article, \'○\', \'<div class="bullet-list-item level-2"></div> \');');
        DB::statement('UPDATE articles SET article=REGEXP_REPLACE(article, \'(&nbsp;){2,}\', \' \');');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // due to the one way nature of the regular expressions there is no option to reverse the migration
    }
}
