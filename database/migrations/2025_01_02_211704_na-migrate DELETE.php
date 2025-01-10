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
            $table->integer('order_count')->default(0)->after('favorites');
            $table->integer('rating')->nullable()->after('favorites');
            $table->text('feedback')->nullable()->after('favorites');
            $table->boolean('newsletter_subscription')->default(false)->after('favorites');
            $table->boolean('has_discount')->default(false)->after('favorites');
            $table->timestamp('last_order')->nullable()->after('favorites');
            $table->timestamp('last_login_at')->nullable()->after('favorites');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('newsletter_subscription');
            $table->dropColumn('last_order');
            $table->dropColumn('last_login_at');
            $table->dropColumn('has_discount');
        });
    }
};
