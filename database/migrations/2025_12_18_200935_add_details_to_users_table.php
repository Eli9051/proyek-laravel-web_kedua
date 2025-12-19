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
    Schema::table('users', function (Blueprint $table) {
        // Menambah identitas karyawan
        $table->string('nik')->unique()->nullable()->after('email');
        $table->string('position')->nullable()->after('nik'); // Jabatan
        
        // Kolom khusus untuk AI Prediksi
        $table->integer('resign_risk_score')->default(0)->after('position'); 
        $table->text('ai_recommendation')->nullable(); // Catatan dari AI
        
        // Status Karyawan
        $table->enum('status', ['active', 'leave', 'resigned'])->default('active');
    });
}
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
