<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scientific_articles', function (Blueprint $table): void {
            $table->string('researcher_name')->nullable()->after('title');
            $table->text('keywords')->nullable()->after('excerpt');
            $table->string('pdf_path')->nullable()->after('keywords');
        });
    }

    public function down(): void
    {
        Schema::table('scientific_articles', function (Blueprint $table): void {
            $table->dropColumn(['researcher_name', 'keywords', 'pdf_path']);
        });
    }
};
