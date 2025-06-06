<?php

namespace Database\Seeders;

use App\Models\FoodItem;
use App\Models\InventoryTransaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventoryTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = User::all()->where('role', 'staff');
        $foods = FoodItem::all();

        foreach ($foods as $food) {
            InventoryTransaction::create([
                'food_item_id' => $food->id,
                'quantity' => fake()->numberBetween(0, 50),
                'transaction_type' => fake()->randomElement(['purchase', 'sales', 'waste', 'adjustment']),
                'transaction_date' => fake()->dateTimeBetween('-1 month', 'now'),
                'note' => fake()->sentence(),
                'staff_id' => $staff->random()->id,
            ]);
        }
    }
}
