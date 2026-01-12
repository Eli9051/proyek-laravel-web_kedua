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
        Schema::table('payrolls', function (Blueprint $table) {
            // Hapus 'after(basic_salary)' dan ganti dengan posisi umum atau hapus saja 'after' nya
            $table->decimal('overtime_hours', 8, 2)->default(0)->after('id');
            $table->decimal('overtime_pay', 15, 2)->default(0)->after('overtime_hours');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['overtime_hours', 'overtime_pay']);
        });
    }
};
