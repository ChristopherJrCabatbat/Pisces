<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Customer or delivery recipient's name
            $table->string('email');
            $table->string('contact_number'); // Contact number of the customer or recipient

            $table->string('rider')->nullable();
            $table->text('address'); // Delivery address

            $table->text('order');
            $table->text('quantity');

            $table->integer('shipping_fee')->default(0);

            $table->string('mode_of_payment');
            $table->string('note')->nullable();
            $table->string('status');
            $table->decimal('total_price', 8, 2);

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};