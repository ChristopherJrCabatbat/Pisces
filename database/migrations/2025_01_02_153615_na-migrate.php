<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable()->after('favorites');
            $table->boolean('has_discount')->default(false)->after('favorites');
            $table->boolean('newsletter_subscription')->default(false)->after('favorites');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_login_at');
            $table->dropColumn('has_discount');
            $table->dropColumn('newsletter_subscription');
        });
    }
};
