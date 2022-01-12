<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewardRedeemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward_redeems', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('user_type', ['USER', 'DRIVER']);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('reward_id');
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
        Schema::dropIfExists('reward_redeems');
    }
}
