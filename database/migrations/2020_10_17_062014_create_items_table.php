<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->integer('cat_id');
            $table->integer('brand_id');
            $table->string('model'); 
            $table->integer('is_serialno')->default(0);  
            $table->string('qty');      
            $table->string('unit');
            $table->integer('price')->default(0);
            $table->string('remark');
            $table->string('photo');
            $table->string('path');
            $table->string('item_code')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('items');
    }
}
