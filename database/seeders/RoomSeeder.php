<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Phòng A1',
                'capacity' => 90,
                'status' => 'active',
                'last_maintenance_date' => now()->subMonth(),
                'seat_algorithms' => '{"check_lonely":true,"check_sole":true,"check_diagonal":true}',
                'created_at' => now()
            ],
            [
                'name' => 'Phòng A2',
                'capacity' => 90,
                'status' => 'active',
                'last_maintenance_date' => now()->subDays(40),
                'seat_algorithms' => '{"check_lonely":true,"check_sole":false,"check_diagonal":true}',
                'created_at' => now()
            ],
            [
                'name' => 'Phòng P1',
                'capacity' => 120,
                'status' => 'maintenance',
                'last_maintenance_date' => now()->subMonths(2),
                'seat_algorithms' => '{"check_lonely":false,"check_sole":true,"check_diagonal":true}',
                'created_at' => now()
            ],
            [
                'name' => 'Phòng P2',
                'capacity' => 120,
                'status' => 'active',
                'last_maintenance_date' => now()->subDays(65),
                'seat_algorithms' => '{"check_lonely":false,"check_sole":false,"check_diagonal":true}',
                'created_at' => now()
            ],
            [
                'name' => 'Phòng B1',
                'capacity' => 70,
                'status' => 'inactive',
                'last_maintenance_date' => now()->subMonths(3),
                'seat_algorithms' => '{"check_lonely":true,"check_sole":true,"check_diagonal":false}',
                'created_at' => now()
            ],
            [
                'name' => 'Phòng B2',
                'capacity' => 80,
                'status' => 'active',
                'last_maintenance_date' => now()->subDays(12),
                'seat_algorithms' => '{"check_lonely":true,"check_sole":true,"check_diagonal":true}',
                'created_at' => now()
            ],
            [
                'name' => 'Phòng C1',
                'capacity' => 150,
                'status' => 'active',
                'last_maintenance_date' => now()->subDays(20),
                'seat_algorithms' => '{"check_lonely":true,"check_sole":true,"check_diagonal":false}',
                'created_at' => now()
            ],
            [
                'name' => 'Phòng D3',
                'capacity' => 110,
                'status' => 'active',
                'last_maintenance_date' => now()->subDays(3),
                'seat_algorithms' => '{"check_lonely":true,"check_sole":false,"check_diagonal":true}',
                'created_at' => now()
            ]
        ];

        Room::insert($data);
    }
}
