<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_assigns', function (Blueprint $table) {
            $table->id();
            $table->integer('ticket_id');
            $table->integer('team_id');
            $table->date('assign_date');
            $table->date('appoint_date');
            $table->date('solved_date')->nullable();
            $table->integer('is_solve')->default(0);
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
        Schema::dropIfExists('ticket_assigns');
    }
}
