<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPromocodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promocodes', function (Blueprint $table) {
            $table->dropColumn('expiration');
            $table->enum('user_type', ['NEW','ALL']);
            $table->integer('no_of_rides');
            $table->date('start');
            $table->date('end');
            $table->enum('apply_type', ['DIRECT','PROMOCODE'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
