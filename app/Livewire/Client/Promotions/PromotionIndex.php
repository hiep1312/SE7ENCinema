<?php

namespace App\Livewire\Client\Promotions;

use Livewire\Component;
use App\Models\Promotion;
use App\Models\Movie;
use App\Models\PromotionUsage;
use Livewire\WithPagination;

class PromotionIndex extends Component
{
    use WithPagination;
    public $expandedPromotionId = null;

    public function togglePromotion($promotionId)
    {
        $this->expandedPromotionId = $this->expandedPromotionId === $promotionId ? null : $promotionId;
    }

    public function render()
    {
        $user = auth('web')->user() ?? request()->user();
        $promotions = Promotion::where('status', 'active')
            ->where('end_date', '>=', now())
            ->orderByDesc('id')
            ->paginate(15, ['*'], 'vouchers');

        $promotionUsedIds = [];
        if ($user) {
            $promotionUsedIds = PromotionUsage::whereHas('booking', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->pluck('promotion_id')->toArray();
        }

        $hotMovies = Movie::where('status', 'showing')
            // ->whereHas('showtimes', function($q) {
            //     $q->where('start_time', '>=', now())->where('status', 'active');
            // })
            ->where('age_restriction', '!=', 'C')
            ->orderByDesc('release_date')
            ->limit(15)
            ->get();
        return view('livewire.client.promotions.promotion-index', [
            'promotions' => $promotions,
            'hotMovies' => $hotMovies,
            'promotionUsedIds' => $promotionUsedIds,
        ]);
    }
}
