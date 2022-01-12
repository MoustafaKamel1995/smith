<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('user_type', ['USER', 'DRIVER', 'BOTH']);
            $table->unsignedInteger('country_id');
            $table->unsignedInteger('city_id');
            $table->string('restaurant_name');
            $table->string('image')->nullable();
            $table->string('amount');
            $table->boolean('free');
            $table->string('item_name');
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
        Schema::dropIfExists('rewards');
    }
}
