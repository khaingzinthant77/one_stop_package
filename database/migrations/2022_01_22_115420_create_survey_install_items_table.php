<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyInstallItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_install_items', function (Blueprint $table) {
            $table->id();
            $table->integer('survey_id');
            $table->integer('item_id');
            $table->integer('item_price')->default(0);
            $table->integer('cat_id');
            $table->integer('cat_price')->default(0);
            $table->integer('qty')->default(0);
            $table->integer('amount')->default(0);
            $table->boolean('is_serial_no')->default(0);
            $table->integer('service_charge')->default(0);
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
        Schema::dropIfExists('survey_install_items');
    }
}
