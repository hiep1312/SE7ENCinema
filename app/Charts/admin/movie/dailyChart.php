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
        return [
            'bookingStatByDate' => $bookingStatByDate,
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
        $paidJs = json_encode($paid);
        $cancelledJs = json_encode($cancelled);
        $totalRevenueJs = json_encode($totalRevenue);
        $categoriesJs = json_encode($categories);
        /* Viết cấu hình biểu đồ tại đây */
        return <<<JS
        {
            series: [
                {
                    name: $categoriesJs,
                    data: $totalRevenueJs
                },
                {
                    name: $categoriesJs,
                    data: $totalRevenueJs
                },
            ],
            chart: {
                type: "line",
                height: 380,
                toolbar: { show: true },
                zoom: { enabled: false },
            },
            dataLabels: { enabled: false },
            stroke: {
                curve: "smooth",
                width: 4,
            },
            markers: {
                size: 4,
                strokeWidth: 2,
                hover: { size: 7 },
            },
            grid: {
                borderColor: "#495057",
                row: { colors: ["transparent"], opacity: 0.5 },
            },
            xaxis: {
                categories: $categoriesJs,
                labels: {
                    style: {
                        colors: '#adb5bd',
                        fontSize: '12px',
                        fontWeight: 600
                    }
                }
            },
            yaxis: {
                title: {
                    text: "Doanh thu",
                    style: {
                        color: "#e4e4e4ff",
                        fontSize: '13px',
                        fontWeight: 500
                        }
                },
                labels: {
                    formatter: function (value) {
                    if (value >= 1000000) {
                        return (value / 1000000).toFixed(1) + " triệu";
                    } else if (value >= 1000) {
                        return (value / 1000).toFixed(0) + " nghìn";
                    }
                    return value; // nhỏ hơn 1000 thì giữ nguyên
                    },
                    style: {
                        colors: '#adb5bd', /* Muted text color */
                        fontSize: '12px'
                    }
                },
            },
            legend: {
                fontSize:'14px',
                labels: {
                    colors: '#adb5bd',
                    useSeriesColors: false
                },
                position: "top",
                horizontalAlign: "center",
                offsetY: 0,
            },
            colors: ["#008FFB", "#00E396"],
            tooltip: {
                theme: 'dark',
                custom: function({series, seriesIndex, dataPointIndex, w}) {
                    const paid = $paidJs;
                    const cancelled = $cancelledJs;
                    const totalRevenue = $totalRevenueJs;
                    const value = paid[dataPointIndex];                    
                    const cancelledValue = cancelled[dataPointIndex];
                    const revenue = totalRevenue.map(n => n.toLocaleString('vi'))[dataPointIndex];
                    return `
                        <div style="
                            background: #ffffffff;
                            color: #000000ff;
                            padding: 15px;
                            border-radius: 10px;
                            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                            min-width: 200px;
                        ">
                            <div style="margin-bottom: 6px;">
                                🎟️ Vé bán: <strong>\${value}</strong>
                            </div>
                            <div style="margin-bottom: 6px;">
                                ❌ Vé lỗi: <strong>\${cancelledValue}</strong>
                            </div>
                            <div style="margin-bottom: 6px;">
                                💵 Doanh thu: <strong>\${revenue}</strong>
                            </div>
                        </div>
                    `;
                }
            }
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
