<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no');
            $table->string('name');
            $table->string('phone_no');
            $table->integer('tsh_id');
            $table->string('address');
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->integer('is_solve')->default(0);
            $table->integer('assign_status')->default(0);
            $table->integer('survey_type')->default(1);//1=new,2=ticket
            $table->boolean('archive_status')->default(1);//1=show,2=0
            $table->boolean('is_install')->default(1);
            $table->string('not_install_remark')->nullable();
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
        Schema::dropIfExists('surveys');
    }
}
