<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('roles')) {
            DB::table('roles')
                ->where('name', 'trainer')
                ->where('guard_name', 'web')
                ->update(['name' => 'teacher']);
        }

        if (Schema::hasTable('users')) {
            DB::table('users')
                ->where('role', 'trainer')
                ->update(['role' => 'teacher']);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('roles')) {
            DB::table('roles')
                ->where('name', 'teacher')
                ->where('guard_name', 'web')
                ->update(['name' => 'trainer']);
        }

        if (Schema::hasTable('users')) {
            DB::table('users')
                ->where('role', 'teacher')
                ->update(['role' => 'trainer']);
        }
    }
};
