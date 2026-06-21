<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->json('name_translations')->nullable()->after('name');
            $table->json('description_translations')->nullable()->after('description');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->json('name_translations')->nullable()->after('name');
            $table->json('description_translations')->nullable()->after('description');
        });

        DB::table('categories')
            ->select(['id', 'name', 'description'])
            ->orderBy('id')
            ->get()
            ->each(function (object $category): void {
                DB::table('categories')
                    ->where('id', $category->id)
                    ->update([
                        'name_translations' => json_encode(['fr' => $category->name], JSON_UNESCAPED_UNICODE),
                        'description_translations' => $category->description
                            ? json_encode(['fr' => $category->description], JSON_UNESCAPED_UNICODE)
                            : null,
                    ]);
            });

        DB::table('products')
            ->select(['id', 'name', 'description'])
            ->orderBy('id')
            ->get()
            ->each(function (object $product): void {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update([
                        'name_translations' => json_encode(['fr' => $product->name], JSON_UNESCAPED_UNICODE),
                        'description_translations' => $product->description
                            ? json_encode(['fr' => $product->description], JSON_UNESCAPED_UNICODE)
                            : null,
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_translations', 'description_translations']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name_translations', 'description_translations']);
        });
    }
};
