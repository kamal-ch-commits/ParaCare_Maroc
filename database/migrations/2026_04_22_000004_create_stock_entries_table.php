<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->date('entry_date');
            $table->timestamps();

            $table->index('entry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_entries');
    }
};
