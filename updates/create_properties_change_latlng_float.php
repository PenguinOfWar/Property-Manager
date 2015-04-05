<?php namespace RainLab\Blog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use DB;

class CreatePropertiesChangeLatLngFloat extends Migration
{

    public function up()
    {
        DB::statement('ALTER TABLE ctmh_propertymanager_properties MODIFY COLUMN latitude DECIMAL(10,8)');
        DB::statement('ALTER TABLE ctmh_propertymanager_properties MODIFY COLUMN longitude DECIMAL(11,8)');
    }

}