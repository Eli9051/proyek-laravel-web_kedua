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
            $table->foreignId('user_id')->constrained();
            $table->date('date'); // Satu baris satu hari
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('location_in')->nullable(); // Geotagging
            $table->string('location_out')->nullable(); //
            $table->enum('status', ['valid', 'jarak_jauh', 'pending_approval'])->default('valid');
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
