<?php namespace ctmh\PropertyManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePropertiesPrice extends Migration
{

    public function up()
    {
        Schema::table('ctmh_propertymanager_properties', function($table)
        {
            $table->float('price');
        });
    }

    public function down()
    {
        Schema::table('ctmh_propertymanager_properties', function($table)
        {
            $table->dropColumn('price');
        });
    }
}