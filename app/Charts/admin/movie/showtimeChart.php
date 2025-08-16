<?php

namespace App\Charts\admin\movie;

use App\Models\Booking;
use Carbon\Carbon;

class showtimeChart
{
    protected $data;
    protected $movie;

    public function __construct($movie)
    {
        $this->movie = $movie;
    }
    protected function queryData(?string $filter = null)
    {
        /* Viết truy vấn CSDL tại đây */
        $bookingChart = Booking::whereHas('showtime', function ($q) {
            $q->where('movie_id', $this->movie->id);
        })->with(['showtime.room'])->get();
        $showtimes = $bookingChart
            ->pluck('showtime')
            ->unique();
        $fromShowtime = Carbon::now()->subDays(3);
        if ($fromShowtime) {
            $showtimes = $showtimes->filter(function ($showtime) use ($fromShowtime) {
                return Carbon::parse($showtime->start_time)->gte($fromShowtime);
            })->values();
        }
        $bookingCountFormatted = $showtimes
            ->filter(fn($showtime) => $showtime->room)
            ->map(function ($showtime) use ($bookingChart) {
                $timeKey = Carbon::parse($showtime->start_time)->format('H:i');
                $capacity = $showtime->room->capacity;
                $bookingsOfShowtime = $bookingChart->filter(function ($booking) use ($showtime) {
                    return $booking->showtime->id === $showtime->id;
                });
                return [
                    'timeKey' => $timeKey,
                    'paid' => $bookingsOfShowtime->where('status', 'paid')->count(),
                    'failed' => $bookingsOfShowtime->where('status', 'failed')->count(),
                    'capacity' => $capacity,
                    'revenue' => $bookingsOfShowtime->where('status', 'paid')->sum('total_price'),
                ];
            })
            ->groupBy('timeKey')
            ->map(function ($items) {
                return [
                    'paid' => $items->sum('paid'),
                    'failed' => $items->sum('failed'),
                    'capacity' => $items->sum('capacity'),
                    'revenue' => $items->sum('revenue'),
                ];
            })
            ->sortKeys();
    }

    public function loadData(?string $filter = null)
    {
        $this->data = $this->queryData($filter);
        dd($this->data);
    }

    protected function bindDataToElement()
    {
        return "document.getElementById('showtimeChart')";
    }

    protected function buildChartConfig()
    {
        /* Viết cấu hình biểu đồ tại đây */
        return <<<JS
        {

        }
        JS;
    }

    public function getFilterText(string $filterValue)
    {
        return match ($filterValue) {
            default => "N/A"
        };
    }

    public function getChartConfig()
    {
        return $this->buildChartConfig();
    }

    public function getData()
    {
        return $this->data;
    }

    public function getEventName()
    {
        return "updateDatashowtimeChart";
    }

    public function compileJavascript()
    {
        $ctxText = "ctxshowtimeChart";
        $optionsText = "optionsshowtimeChart";
        $chartText = "chartshowtimeChart";
        echo <<<JS
         Livewire.on("{$this->getEventName()}", async function ([data]){
            await new Promise(resolve => setTimeout(resolve));
            const {$ctxText} = {$this->bindDataToElement()};
            if($ctxText){
                if(window.{$chartText} && document.contains(window.{$chartText}.getElement())) (window.{$optionsText} = new Function("return " + data)()) && (window.{$chartText}.updateOptions(window.{$optionsText}));
                else (window.{$optionsText} = {$this->buildChartConfig()}) &&  (window.{$chartText} = createScChart({$ctxText}, {$optionsText}));
            }
        });
        JS;
    }
}
