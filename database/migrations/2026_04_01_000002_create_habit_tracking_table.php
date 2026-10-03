<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habit_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habit_id')->constrained()->onDelete('cascade');
            $table->date('completed_date');
            $table->timestamps();
            $table->unique(['habit_id', 'completed_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habit_tracking');
    }
};
