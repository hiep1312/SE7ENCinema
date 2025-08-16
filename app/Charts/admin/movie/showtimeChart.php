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
        $fromShowtime = match ($filter) {
            '3_days'    => Carbon::now()->subDays(3),
            '7_days'    => Carbon::now()->subDays(7),
            '15_days'   => Carbon::now()->subDays(15),
            '30_days'   => Carbon::now()->subDays(30),
            '3_months'  => Carbon::now()->subMonths(3)->startOfMonth(),
            '6_months'  => Carbon::now()->subMonths(6)->startOfMonth(),
            '9_months'  => Carbon::now()->subMonths(9)->startOfMonth(),
            '1_years'   => Carbon::now()->subYears(1)->startOfYear(),
            '2_years'   => Carbon::now()->subYears(2)->startOfYear(),
            '3_years'   => Carbon::now()->subYears(3)->startOfYear(),
            '6_years'   => Carbon::now()->subYears(6)->startOfYear(),
            default     => null,
        };
        /* Viết truy vấn CSDL tại đây */
        $bookingChart = Booking::whereHas('showtime', function ($q) {
            $q->where('movie_id', $this->movie->id);
        })->with(['showtime.room'])->get();
        $showtimes = $bookingChart
            ->pluck('showtime')
            ->unique();
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
        return $bookingCountFormatted;
    }

    public function loadData(?string $filter = null)
    {
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement()
    {
        return "document.getElementById('showtimeChart')";
    }

    protected function buildChartConfig()
    {
        /* Viết cấu hình biểu đồ tại đây */
        $paidCounts = json_encode($this->data->pluck('paid')->toArray());
        $showtimeDate = json_encode($this->data->keys()->toArray());
        $capacityCounts = json_encode($this->data->pluck('capacity')->toArray());
        $maxCapacityCounts = json_encode($this->data->pluck('capacity')->max());
        return <<<JS
        {
            series: [
                {
                    name: 'Vé đã bán',
                    data: $paidCounts
                },
                {
                    name: 'Sức chứa',
                    data: $capacityCounts
                }
            ],
            chart: {
                type: 'bar',
                height: 400,
                background: 'transparent',
                toolbar: { show: true },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: ['#4285F4', '#34A853'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    endingShape: 'rounded',
                    borderRadius: 6
                }
            },
            dataLabels: { enabled: false },
            stroke: { show: false },
            xaxis: {
                categories: $showtimeDate,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: {
                        colors: '#adb5bd',
                        fontSize: '12px',
                        fontWeight: 600
                    }
                }
            },
            yaxis: {
                min: 0,
                max: $maxCapacityCounts,
                tickAmount: 7,
                labels: {
                    style: {
                        colors: '#adb5bd', /* Muted text color */
                        fontSize: '12px'
                    }
                }
            },
            grid: {
                show: true,
                borderColor: '#495057', /* Darker grid lines */
                strokeDashArray: 2,
                xaxis: { lines: { show: false } }
            },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'left',
                offsetY: -10,
                labels: { colors: '#f8f9fa' }, /* Light text color */
                markers: {
                    width: 12,
                    height: 12,
                    fillColors: ['#4285F4', '#34A853'],
                    radius: 3
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
            }
        }
        JS;
    }

    public function getFilterText(string $filterValue)
    {
        return match ($filterValue) {
            '3_days' => '3 ngày gần nhất',
            '7_days' => '7 ngày gần nhất',
            '15_days' => '15 ngày gần nhất',
            '30_days' => '30 ngày gần nhất',
            '3_months' => '3 tháng gần nhất',
            '6_months' => '6 tháng gần nhất',
            '9_months' => '9 tháng gần nhất',
            '1_year' => '1 năm gần nhất',
            '2_years' => '2 năm gần nhất',
            '3_years' => '3 năm gần nhất',
            '6_years' => '6 năm gần nhất',
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
