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
                $capacity = $showtime->room->seats->count();
                $bookingsOfShowtime = $bookingChart->filter(function ($booking) use ($showtime) {
                    return $booking->showtime->id === $showtime->id;
                });
                return [
                    'timeKey' => $timeKey,
                    'paid' => $bookingsOfShowtime->where('status', 'paid')->count(),
                    'seatsCount' => $bookingsOfShowtime->where('status', 'paid')->pluck('seats')->flatten()->count(),
                    'failed' => $bookingsOfShowtime->whereIn('status', ['failed','expired'])->count(),
                    'capacity' => $capacity,
                    'revenue' => $bookingsOfShowtime->where('status', 'paid')->sum('total_price'),
                ];
            })
            ->groupBy('timeKey')
            ->map(function ($items) {
                return [
                    'paid' => $items->sum('paid'),
                    'failed' => $items->sum('failed'),
                    'seatsCount' => $items->sum('seatsCount'),
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
        $failedCounts = json_encode($this->data->pluck('failed')->toArray());
        $revenueShowtime = json_encode($this->data->pluck('revenue')->toArray());
        $seatsCount = json_encode($this->data->pluck('seatsCount')->toArray());
        $showtimeDate = json_encode($this->data->keys()->toArray());
        $capacityCounts = json_encode($this->data->pluck('capacity')->toArray());
        $maxCapacityCounts = json_encode($this->data->pluck('capacity')->max());
        return <<<JS
        {
            series: [
                {
                    name: 'Sức chứa',
                    data: $capacityCounts
                },
                {
                    name: 'Ghế đã bán',
                    data: $seatsCount
                },
                {
                    name: 'Ngày so sánh',
                    data: $failedCounts
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
            colors: ['#34A853','#4285F4', '#EA4335'],
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
                    fillColors: ['#34A853','#4285F4','#EA4335'],
                    radius: 3
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                theme: 'dark',
                custom: function({series, seriesIndex, dataPointIndex, w}) {
                    const times = $showtimeDate;           
                    const soldSeats = $seatsCount;    
                    const paid = $paidCounts;    
                    const failed = $failedCounts;        
                    const capacity = $capacityCounts;     
                    const revenue = $revenueShowtime;     

                    const time = times[dataPointIndex];
                    const paidCount = paid[dataPointIndex];
                    const sold = soldSeats[dataPointIndex];
                    const failedCount = failed[dataPointIndex];
                    const cap = capacity[dataPointIndex];
                    const percentage = cap > 0 ? ((sold / cap) * 100).toFixed(1) : 0;
                    const revenueFormatted = Number(revenue[dataPointIndex]).toLocaleString('vi');
                    return `
                        <div style="
                            background: #ffffffff;
                            color: #000000ff;
                            padding: 15px;
                            border-radius: 10px;
                            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                            min-width: 200px;
                            border: 1px solid #495057;">
                            <div style="font-weight: 600; font-size: 14px; margin-bottom: 8px;">
                                🎬 Suất \${time}
                            </div>
                            <div style="margin-bottom: 6px;">
                                🎟️ Ghế đã bán: <strong>\${sold}</strong>
                            </div>
                            <div style="margin-bottom: 6px;">
                                🎟️ Vé đã bán: <strong>\${paidCount}</strong>
                            </div>
                            <div style="margin-bottom: 6px;">
                                🪑 Sức chứa: \${cap}
                            </div>
                            <div style="margin-bottom: 6px;">
                                📊 Tỷ lệ lấp đầy: <strong>\${percentage}%</strong>
                            </div>
                            <div style="margin-bottom: 6px;">
                                ❌ Vé bị lỗi: <strong>\${failedCount}</strong>
                            </div>
                            <div style="margin-bottom: 6px;">
                                💵 Doanh thu: <strong>\${revenueFormatted} ₫</strong>
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
