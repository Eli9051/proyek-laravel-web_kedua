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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang absen 
            $table->date('date'); // Tanggal hari ini 
            $table->time('check_in')->nullable(); // Jam masuk 
            $table->time('check_out')->nullable(); // Jam pulang 
            $table->string('status', 20)->default('Hadir'); // Status kehadiran [cite: 45]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
