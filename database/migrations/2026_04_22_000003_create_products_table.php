<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->default(0);
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->unsignedInteger('minimum_threshold')->default(0);
            $table->date('expiration_date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['name', 'code']);
            $table->index('expiration_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
