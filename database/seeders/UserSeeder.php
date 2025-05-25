<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 3 bản ghi thủ công theo role
        User::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'name' => 'Admin User',
            'phone' => '0123456789',
            'address' => 'Admin Address',
            'avatar' => fake()->imageUrl(200, 200, 'people'),
            'birthday' => '1990-01-01',
            'gender' => 'man',
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'email' => 'staff@example.com',
            'password' => Hash::make('staff123'),
            'name' => 'Staff User',
            'phone' => '0987654321',
            'address' => 'Staff Address',
            'avatar' => fake()->imageUrl(200, 200, 'people'),
            'birthday' => '1992-02-02',
            'gender' => 'woman',
            'role' => 'staff',
            'status' => 'active',
        ]);

        User::create([
            'email' => 'user@example.com',
            'password' => Hash::make('user123'),
            'name' => 'Normal User',
            'phone' => '0912345678',
            'address' => 'User Address',
            'avatar' => fake()->imageUrl(200, 200, 'people'),
            'birthday' => '1995-03-03',
            'gender' => 'other',
            'role' => 'user',
            'status' => 'active',
        ]);

        // Dữ liệu fake
        foreach (range(1, 17) as $i) { // còn lại tạo cho đủ 20
            User::create([
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('12345678'),
                'name' => fake()->name(),
                'phone' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'avatar' => fake()->imageUrl(200, 200, 'people'),
                'birthday' => fake()->date(),
                'gender' => fake()->randomElement(['man', 'woman', 'other']),
                'role' => fake()->randomElement(['user', 'staff']),
                'status' => fake()->randomElement(['active', 'inactive', 'banned']),
            ]);
        }
    }
}
