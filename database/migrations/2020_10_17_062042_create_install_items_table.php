<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('install_items', function (Blueprint $table) {
            $table->id();
            $table->integer('survey_id');
            $table->integer('item_id');
            $table->integer('cat_id');
            $table->integer('brand_id');
            $table->string('model_id');
            $table->string('qty');
            $table->string('price');
            $table->string('amount');
            $table->string('unit')->nullable();
            $table->string('cat_price');
            $table->string('serial_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('install_items');
    }
}
