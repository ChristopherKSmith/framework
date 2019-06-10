<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

Class RemovePriceColumnsFromProductsTable extends Migration
  {
      public function up()
      {
          Schema::table('products', function($table) {
             $table->dropColumn('sku');
             $table->dropColumn('price');
             $table->dropColumn('stock');

          });
      }

      public function down()
      {
          Schema::table('products', function($table) {
             
            $table->string('sku');
            $table->decimal('price', 15, 4)->nullable();
            $table->decimal('stock', 15, 4)->default(0);

          });
      }
  }

?>