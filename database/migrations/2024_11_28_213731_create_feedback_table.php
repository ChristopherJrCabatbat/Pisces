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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name'); // The customer's name
            $table->string('order_number'); // The unique identifier for the order
            $table->text('menu_items'); // List of menu items related to the feedback
            $table->text('feedback_text')->nullable(); // Optional feedback text from the customer
            $table->decimal('rating', 2, 1)->nullable(); // Optional numeric rating (e.g., 1 to 5)
            $table->decimal('rider_rating', 2, 1)->nullable();
            $table->string('rider_name')->nullable();
            $table->string('sentiment')->nullable(); // Optional sentiment analysis result (e.g., 'Positive', 'Negative')
            $table->text('response')->nullable(); // Optional response from admin or staff
            $table->timestamps(); // Created_at and Updated_at
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
