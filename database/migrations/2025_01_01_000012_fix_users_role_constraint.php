<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if role column exists before trying to update
        if (! Schema::hasColumn('users', 'role')) {
            return; // Skip if column doesn't exist
        }

        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            // PostgreSQL syntax
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
            DB::statement("UPDATE users SET role = 'siswa' WHERE role = 'user'");
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('superadmin', 'siswa'))");
        } else {
            // SQLite and other databases - just update the values
            // SQLite doesn't support DROP CONSTRAINT, and constraints are applied at table creation
            DB::statement("UPDATE users SET role = 'siswa' WHERE role = 'user'");
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('superadmin', 'user'))");
        } else {
            DB::statement("UPDATE users SET role = 'user' WHERE role = 'siswa'");
        }
    }
};
