<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->float('amount', 10, 2);
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('payment_type_id')->nullable();
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('payment_type_id')->references('id')->on('payment_types')->onDelete('set null');
            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
