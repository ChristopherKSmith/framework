<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Vanilo\Framework\Models\ShippingMethodType;

class CreateShippingMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('type', [
                ShippingMethodType::FLATRATE,
                ShippingMethodType::FREESHIPPING,
                ShippingMethodType::STOREPICKUP
            ])->default(ShippingMethodType::FLATRATE);
            $table->string('zone');
            $table->char('country_id', 2)->nullable();
            $table->boolean('is_active');
            $table->decimal('rate', 15, 4)->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('country_id')
                  ->references('id')
                  ->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_methods');
    }
}
