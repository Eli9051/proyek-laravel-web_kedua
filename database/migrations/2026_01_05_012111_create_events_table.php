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
        Schema::create('events', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('event_date');
            $table->string('location')->nullable();
            $table->string('color')->default('#0090e7'); // Untuk pembeda visual
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
