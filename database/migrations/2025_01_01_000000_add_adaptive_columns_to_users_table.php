<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add adaptive learning columns if they don't exist yet
            if (! Schema::hasColumn('users', 'xp')) {
                $table->integer('xp')->default(0)->after('role');
            }
            if (! Schema::hasColumn('users', 'level')) {
                $table->integer('level')->default(1)->after('xp');
            }
            if (! Schema::hasColumn('users', 'placement_done')) {
                $table->boolean('placement_done')->default(false)->after('level');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['xp', 'level', 'placement_done']);
        });
    }
};
