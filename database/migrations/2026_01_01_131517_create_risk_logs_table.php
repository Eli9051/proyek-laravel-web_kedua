<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('risk_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('score'); // Skor 0-100
            $table->string('status'); // Rendah, Sedang, Tinggi
            $table->string('period'); // Contoh: "January 2026"
            $table->text('reason')->nullable(); // Alasan kenapa skor naik (misal: sering terlambat)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_logs');
    }
};
