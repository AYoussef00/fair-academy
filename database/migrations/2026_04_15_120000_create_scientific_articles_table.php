<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scientific_articles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('category', 120);
            $table->text('excerpt');
            $table->longText('content');
            $table->string('status', 20)->default('pending'); // pending, approved, rejected
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scientific_articles');
    }
};
