<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amounts', function (Blueprint $table) {
            $table->id();
            $table->integer('survey_id')->nullable();
            $table->integer('ticket_id')->nullable();
            $table->integer('sub_total');
            $table->integer('total_amt');
            $table->integer('install_charge');
            $table->integer('service_charge')->nullable();//ticket
            $table->integer('cabling_charge')->default(0);
            $table->integer('is_cloud')->default(0);
            $table->integer('cloud_charge')->nullable();
            $table->integer('is_foc')->default(0);
            $table->integer('discount')->nullable();
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
        Schema::dropIfExists('amounts');
    }
}
