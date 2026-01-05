<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Karyawan>
 */
class KaryawanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(), // Tambahkan ini!
            'password' => bcrypt('password123'),      // Tambahkan ini!
            'role' => 'karyawan',                     // Tambahkan ini agar muncul di daftar karyawan!
            'position' => 'Staff IT',
            'resign_risk_score' => rand(10, 90),
            'status' => 'active'
        ];
    }
}
