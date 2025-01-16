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
        Schema::table('users', function (Blueprint $table) {
            $table->string('shipping_fee')->default(0)->after('contact_number');
            $table->string('barangay')->nullable()->after('contact_number');
            $table->string('purok')->nullable()->after('contact_number');
            $table->string('house_num')->nullable()->after('contact_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('shipping_fee');
            $table->dropColumn('barangay');
            $table->dropColumn('purok');
            $table->dropColumn('house_num');
        });
    }
};
