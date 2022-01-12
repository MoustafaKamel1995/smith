<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRideRequestPaymentRefundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('transport')->create('ride_request_payment_refund', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ride_request_id');
            $table->string('response_status')->nullable();
            $table->string('message')->nullable();
            $table->string('reference')->nullable();
            $table->float('amount',10, 2)->nullable();
            $table->string('currency')->nullable();
            $table->integer('deducted_amount')->nullable();
            $table->string('refund_status')->nullable();
            $table->string('refunded_by')->nullable();
            $table->string('domain')->nullable();
            $table->boolean('fully_deducted')->default(false);            
            $table->text('data_json')->nullable();
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
        Schema::dropIfExists('ride_request_payment_refund');
    }
}
