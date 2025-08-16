<?php

namespace App\Charts\admin\movie;

use App\Models\Booking;
use Carbon\Carbon;

class dailyChart
{
    protected $data;
    public $dailyChart = 'monthly';
    protected $movie;

    public function __construct($movie)
    {
        $this->movie = $movie;
    }
    protected function queryData(?string $filter = null)
    {
        // dd($filter);
        $bookingChart = Booking::whereHas('showtime', function ($q) {
            $q->where('movie_id', $this->movie->id);
        })->with(['showtime.room'])->get();
        $dates = [];
        if ($this->dailyChart == 'monthly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->startOfMonth()->subMonths($i);
                $dates[] = $date;
            }
        } elseif ($this->dailyChart == 'daily') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dates[] = $date;
            }
        } elseif ($this->dailyChart == 'yearly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subYears($i);
                $dates[] = $date;
            }
        }
        $bookingStatByDate = collect($dates)->mapWithKeys(function ($date) use ($bookingChart) {
            if ($this->dailyChart == 'monthly') {
                $dateStr = $date->format('m-Y');
            } elseif ($this->dailyChart == 'daily') {
                $dateStr = $date->format('m-d');
            } elseif ($this->dailyChart == 'yearly') {
                $dateStr = $date->format('Y');
            }
            $bookingsOnDate = $bookingChart->filter(function ($booking) use ($date) {
                $bookingDate = Carbon::parse($booking->showtime->start_time);
                if ($this->dailyChart == 'monthly') {
                    return $bookingDate->year === $date->year && $bookingDate->month === $date->month;
                } elseif ($this->dailyChart == 'daily') {
                    return $bookingDate->isSameDay($date);
                } elseif ($this->dailyChart == 'yearly') {
                    return $bookingDate->year === $date->year;
                }
            });
            $paidCount = $bookingsOnDate->where('status', 'paid')->count();
            $cancelledCount = $bookingsOnDate->whereIn('status', ['failed', 'expired'])->count();
            $totalRevenue = $bookingsOnDate->where('status', 'paid')->sum('total_price');
            return [
                $dateStr => [
                    'paid' => $paidCount,
                    'cancelled' => $cancelledCount,
                    'totalRevenue' => $totalRevenue,
                ]
            ];
        });
        $totalMax = $bookingStatByDate->pluck('paid')->max();
        return [
            'bookingStatByDate' => $bookingStatByDate,
            'totalMax' => $totalMax
        ];
    }

    public function loadData(?string $filter = null)
    {
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement()
    {
        return "document.getElementById('dailyChart')";
    }

    protected function buildChartConfig()
    {
        $paid = $this->data['bookingStatByDate']->pluck('paid')->toArray();
        $cancelled = $this->data['bookingStatByDate']->pluck('cancelled')->toArray();
        $totalRevenue = $this->data['bookingStatByDate']->pluck('totalRevenue')->toArray();
        $categories = $this->data['bookingStatByDate']->keys()->toArray();
        $totalMax = $this->data['totalMax'];
        $paidJs = json_encode($paid);
        $cancelledJs = json_encode($cancelled);
        $totalRevenueJs = json_encode($totalRevenue);
        $categoriesJs = json_encode($categories);
        $totalMaxJs = json_encode($totalMax);
        /* Viết cấu hình biểu đồ tại đây */
        return <<<JS
        {
                series: [{
                    name: 'Số vé đã bán',
                    data: $paidJs
                }],
                chart: {
                    height: 400,
                    type: 'area',
                    background: 'transparent',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true,
                            customIcons: []
                        }
                    },
                    zoom: { enabled: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                    }
                },
                colors: ['#4285F4'],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'vertical',
                        shadeIntensity: 0.3,
                        gradientToColors: ['#4285F4'],
                        inverseColors: false,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                        stops: [0, 100]
                    }
                },
                dataLabels: { enabled: false },
                markers: {
                    size: 6,
                    colors: ['#4285F4'],
                    strokeColors: '#2c3034',
                    strokeWidth: 2,
                    hover: { size: 8 }
                },
                xaxis: {
                    categories: $categoriesJs,
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
                    max: $totalMaxJs,
                    tickAmount: 5,
                    labels: {
                        style: {
                            colors: '#adb5bd',
                            fontSize: '12px'
                        }
                    }
                },
                grid: {
                    show: true,
                    borderColor: '#495057',
                    strokeDashArray: 2
                },
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
        return "updateDatadailyChart";
    }

    public function compileJavascript()
    {
        $ctxText = "ctxdailyChart";
        $optionsText = "optionsdailyChart";
        $chartText = "chartdailyChart";
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
