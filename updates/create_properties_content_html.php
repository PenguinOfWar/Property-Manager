<?php namespace RainLab\Blog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePropertiesContentHtml extends Migration
{

    public function up()
    {
        Schema::table('ctmh_propertymanager_properties', function($table)
        {
            $table->text('content_html')->nullable();
        });
    }

    public function down()
    {
        Schema::table('ctmh_propertymanager_properties', function($table)
        {
            $table->dropColumn('content_html');
        });
    }
}