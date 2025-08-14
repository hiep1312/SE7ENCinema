<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'GIẢM 20% - MÙA HÈ NÓNG BỎNG',
                'description' => '<h3>Ưu đãi 20% cho mọi suất chiếu</h3>
                    <p>Áp dụng cho tất cả các suất chiếu trong tháng hè này.</p>
                    <ul>
                        <li><strong>Thời gian:</strong> 01/06 - 30/06</li>
                        <li>Không áp dụng cùng các mã khuyến mãi khác.</li>
                        <li>Mỗi khách hàng chỉ sử dụng tối đa 2 lần.</li>
                    </ul>',
                'start_date' => '2025-06-01',
                'end_date' => '2025-08-31',
                'discount_type' => 'percent',
                'discount_value' => 20,
                'code' => 'SUMMER20',
                'usage_limit' => 500,
                'min_purchase' => 0,
                'status' => 'active',
            ],
        ];

        $start_date = Carbon::parse(fake()->dateTimeBetween('-1 year', 'now'));
        $end_date = $start_date->copy()->addDays(10);
        foreach (range(1, 20) as $i) {
            Promotion::create([
                'title' => fake()->words(3, true),
                'description' => fake()->optional()->sentence(12),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'discount_type' => ($typePromotion = fake()->randomElement(['percentage', 'fixed_amount'])),
                'discount_value' => ($typePromotion === "percentage" ? fake()->numberBetween(1, 80) : fake()->numberBetween(10000, 100000)),
                'code' => strtoupper(fake()->bothify('PROMO###*****')),
                'usage_limit' => fake()->optional()->numberBetween(1, 1000),
                'min_purchase' => fake()->numberBetween(0, 200000),
                'status' => 'active',
            ]);
        }
    }
}
