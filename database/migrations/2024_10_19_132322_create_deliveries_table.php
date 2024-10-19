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
            $table->string('order'); // The name or description of the order
            $table->string('contact_number'); // Contact number of the customer or recipient
            $table->text('address'); // Delivery address
            $table->integer('quantity'); // Quantity of the items being delivered
            $table->string('mode_of_payment'); // Payment method (e.g., Cash, Credit Card, etc.)
            $table->string('status'); // Delivery status (e.g., Pending, Delivered, Canceled, etc.)
            $table->timestamps(); // Automatically adds created_at and updated_at
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