<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->whereNull('email')
            ->orderBy('id')
            ->each(function (object $user): void {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['email' => "user{$user->id}@placeholder.local"]);
            });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->change();
            $table->string('email')->nullable(false)->change();
            $table->string('role')->default('customer')->change();
        });

        DB::table('users')
            ->whereNull('role')
            ->orWhere('role', '')
            ->update(['role' => 'customer']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->change();
            $table->string('email')->nullable()->change();
            $table->string('role')->default('admin')->change();
        });
    }
};
