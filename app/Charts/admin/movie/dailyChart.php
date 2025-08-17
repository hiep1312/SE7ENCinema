<?php

namespace App\Charts\admin\movie;

use App\Models\Booking;
use Carbon\Carbon;

class dailyChart
{
    protected $data;
    protected $movie;
    protected $fromCheckinChart = '2022-08-01';
    protected $rangeDays = 1000;


    public function __construct($movie)
    {
        $this->movie = $movie;
    }
    protected function queryData(?string $filter = null)
    {
        $fromCheckinChart = Carbon::parse($this->fromCheckinChart)->startOfDay();
        $toCheckinChart = (clone $fromCheckinChart)->addDays($this->rangeDays)->endOfDay();

        $bookingChart = Booking::whereHas('showtime', function ($q) {
            $q->where('movie_id', $this->movie->id);
        })->with(['showtime.room'])->get();

        // Xác định kiểu group dựa vào rangeDays
        $groupType = 'daily'; // default\
        if ($this->rangeDays > 365) $groupType = 'yearly'; // > 1 năm
        elseif ($this->rangeDays > 90) $groupType = 'monthly'; // > 3 tháng
        elseif ($this->rangeDays > 30) $groupType = 'weekly'; // > 1 tháng
        // Tạo các labels và khởi tạo collection
        $dates = collect();
        $current = $fromCheckinChart->copy();

        while ($current <= $toCheckinChart) {
            if ($groupType === 'daily') {
                $dates->push($current->copy());
                $current->addDay();
            } elseif ($groupType === 'weekly') {
                // Lấy ngày đầu tuần
                $startOfWeek = $current->copy()->startOfWeek();
                $endOfWeek = $current->copy()->endOfWeek();
                $dates->push([
                    'start' => $startOfWeek,
                    'end' => $endOfWeek
                ]);
                // Nhảy sang tuần tiếp theo
                $current = $endOfWeek->copy()->addDay();
            } elseif ($groupType === 'monthly') {
                $startOfMonth = $current->copy()->startOfMonth();
                $endOfMonth = $current->copy()->endOfMonth();
                $dates->push([
                    'start' => $startOfMonth,
                    'end' => $endOfMonth
                ]);
                // Nhảy sang tháng tiếp theo
                $current = $endOfMonth->copy()->addDay();
            } elseif ($groupType === 'yearly') {
                $startOfYear = $current->copy()->startOfYear();
                $endOfYear = $current->copy()->endOfYear();
                $dates->push([
                    'start' => $startOfYear,
                    'end' => $endOfYear
                ]);
                $current = $endOfYear->copy()->addDay(); // nhảy sang năm tiếp theo
            }
        }


        // Tạo thống kê theo nhóm
        $bookingStatByDate = $dates->mapWithKeys(function ($date) use ($bookingChart, $groupType) {
            if ($groupType === 'daily') {
                $dateStr = $date->format('m-d');
                $bookingsOnDate = $bookingChart->filter(function ($booking) use ($date) {
                    $bookingDate = Carbon::parse($booking->showtime->start_time);
                    return $bookingDate->isSameDay($date);
                });
            } elseif ($groupType === 'weekly') {
                // $date là mảng ['start' => Carbon, 'end' => Carbon]
                $start = $date['start'];
                $end = $date['end'];
                $dateStr = $start->format('d/m') . ' - ' . $end->format('d/m'); // nhãn trực quan
                $bookingsOnDate = $bookingChart->filter(function ($booking) use ($start, $end) {
                    $bookingDate = Carbon::parse($booking->showtime->start_time);
                    return $bookingDate->between($start, $end);
                });
            } elseif ($groupType === 'monthly') {
                $start = $date['start'];
                $end = $date['end'];
                $dateStr = $start->format('m-Y');
                $bookingsOnDate = $bookingChart->filter(function ($booking) use ($start, $end) {
                    $bookingDate = Carbon::parse($booking->showtime->start_time);
                    return $bookingDate->between($start, $end);
                });
            } elseif ($groupType === 'yearly') {
                $start = $date['start'];
                $end = $date['end'];
                $dateStr = $start->format('Y'); // nhãn là năm
                $bookingsOnDate = $bookingChart->filter(function ($booking) use ($start, $end) {
                    $bookingDate = Carbon::parse($booking->showtime->start_time);
                    return $bookingDate->between($start, $end);
                });
            }

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
                    name: 'Dữ liệu ngày A',
                    data: $totalRevenueJs
                },
                {
                    name: 'Dữ liệu ngày B',
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
