<?php namespace ctmh\PropertyManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePropertiesPrice extends Migration
{

    public function up()
    {
        Schema::table('ctmh_propertymanager_properties', function($table)
        {
            $table->string('location');
            $table->string('postcode');
            $table->string('bedrooms');
            $table->string('receptions');
            $table->string('bathrooms');
            $table->tinyInteger('sold')->default(0);
        });
    }

    public function down()
    {
        Schema::table('ctmh_propertymanager_properties', function($table)
        {
            $table->string('location');
            $table->string('postcode');
            $table->string('bedrooms');
            $table->string('receptions');
            $table->string('bathrooms');
            $table->tinyInteger('sold');
        });
    }
}