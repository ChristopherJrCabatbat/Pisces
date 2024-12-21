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
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Sender (nullable for system messages)
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade'); // Receiver
            $table->string('sender_role'); // Role of the sender (User, Admin, or System)
            $table->text('message_text')->nullable();; // Actual message content
            $table->boolean('is_read')->default(false); // Tracks if the message has been read
            $table->string('image_url')->nullable(); // Path to the uploaded image
            $table->timestamps(); // Includes created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
