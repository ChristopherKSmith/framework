<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_skus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->string('code');
            $table->decimal('cost', 15, 4)->default(0);
            $table->decimal('price', 15, 4)->nullable();
            $table->decimal('stock', 15, 4)->default(0);
            $table->integer('units_sold')->unsigned()->default(0);
            $table->dateTime('last_sale_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_skus');
    }
}
