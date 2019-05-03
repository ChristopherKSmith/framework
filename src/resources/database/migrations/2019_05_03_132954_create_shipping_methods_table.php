<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\ShippingMethodStateProxy;
use App\Models\ShippingZoneTypeProxy;

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
            $table->string('zone');
            $table->decimal('rate', 15, 4)->nullable();
            $table->text('description')->nullable();
            $table->string('state');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('country_id')
                  ->references('id')
                  ->on('countries')
                  ->nullable();
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
