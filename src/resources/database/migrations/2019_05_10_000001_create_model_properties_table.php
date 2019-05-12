<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelPropertiesTable extends Migration
{
    public function up()
    {
        Schema::create('model_properties', function (Blueprint $table) {
            $table->integer('property_id')->unsigned();
            $table->morphs('model');
            $table->timestamps();

            $table->foreign('property_id')
                ->references('id')
                ->on('properties')
                ->onDelete('cascade');

            $table->primary(['property_id', 'model_id', 'model_type'], 'pk_model_properties');
        });
    }

    public function down()
    {
        Schema::drop('model_properties');
    }
}