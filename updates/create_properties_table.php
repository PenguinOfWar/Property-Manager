<?php namespace ctmh\PropertyManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePropertiesTable extends Migration
{

    public function up()
    {
        Schema::create('ctmh_propertymanager_properties', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->string('title')->nullable();
            $table->string('slug')->index();
            $table->text('intro')->nullable();
            $table->text('content')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamps();
            $table->float('latitude');
            $table->float('longitude');
            $table->string('type');
        });
    }

    public function down()
    {
        Schema::drop('ctmh_propertymanager_properties');
    }

}