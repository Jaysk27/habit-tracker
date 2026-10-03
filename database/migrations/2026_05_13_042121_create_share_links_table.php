<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('share_links', function (Blueprint $table) {
            $table->id();
            $table->string('short_code', 16)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('habit_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->integer('month');
            $table->float('completion_rate')->nullable();
            $table->integer('current_streak')->nullable();
            $table->integer('total_completions')->nullable();
            $table->string('og_image_path')->nullable();
            $table->integer('views')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('share_links');
    }
};