<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->default('cash_on_delivery')->after('status');
            $table->string('payment_status')->default('cash_on_delivery')->after('payment_method');
            $table->string('payment_reference')->nullable()->after('payment_status');

            $table->index(['payment_method', 'payment_status'], 'orders_payment_method_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_payment_method_status_index');
            $table->dropColumn(['payment_method', 'payment_status', 'payment_reference']);
        });
    }
};
