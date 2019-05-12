<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariantPropertyValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variant_property_value', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_variant_id')->unsigned();
            $table->integer('property_value_id')->unsigned();
            
            $table->foreign('product_variant_id')
                  ->references('id')
                  ->on('product_variants');

            $table->foreign('property_value_id')
                  ->references('id')
                  ->on('property_values');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_variant_property_value');
    }
}
