<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds role column: trainer | admin | student
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('student')->after('status');
        });

        $this->backfillRoleFromSpatie();
    }

    /**
     * Backfill role from Spatie model_has_roles (instructor -> trainer).
     */
    protected function backfillRoleFromSpatie(): void
    {
        if (! Schema::hasTable('model_has_roles') || ! Schema::hasTable('roles')) {
            return;
        }

        $rows = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', User::class)
            ->select('model_has_roles.model_id', 'roles.name')
            ->get();

        foreach ($rows as $row) {
            $role = $row->name === 'instructor' ? 'trainer' : $row->name;
            if (in_array($role, ['trainer', 'admin', 'student'], true)) {
                DB::table('users')->where('id', $row->model_id)->update(['role' => $role]);
            }
        }

        // Rename Spatie role instructor -> trainer so middleware role:trainer works
        DB::table('roles')->where('name', 'instructor')->where('guard_name', 'web')->update(['name' => 'trainer']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
