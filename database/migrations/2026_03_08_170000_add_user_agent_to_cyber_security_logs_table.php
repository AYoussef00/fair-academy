<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cyber_security_logs', function (Blueprint $table) {
            $table->string('user_agent', 500)->nullable()->after('endpoint');
        });
    }

    public function down(): void
    {
        Schema::table('cyber_security_logs', function (Blueprint $table) {
            $table->dropColumn('user_agent');
        });
    }
};
