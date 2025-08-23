<?php

namespace App\Charts\admin\movie;

use App\Models\Booking;
use App\Models\Showtime;
use Carbon\Carbon;

class showtimeChart
{
    protected $data;
    protected $movie;

    public function __construct($movie)
    {
        $this->movie = $movie;
    }
    protected function queryData(?array $filter = null)
    {
        is_array($filter) && [$fromDate, $rangeDays] = $filter;
        $rangeDays = (int) $rangeDays;

        /* Xử lý thời gian */
        $fromDate = Carbon::parse($fromDate)->startOfDay();
        $toDate = (clone $fromDate)->addDays($rangeDays)->endOfDay();

        /* Lấy showtime trong khoảng thời gian */
        $showtimesA = Showtime::where('movie_id', $this->movie->id)
            ->whereBetween('start_time', [$fromDate, $toDate])
            ->orderBy('start_time')
            ->get();

        /* Lấy booking liên quan */
        $bookingChart = Booking::whereHas('showtime', function ($q) {
            $q->where('movie_id', $this->movie->id);
        })
            ->with(['showtime'])
            ->get();

        /* Hàm xử lý thống kê */
        $processRange = function ($showtimes, $bookings) {
            return $showtimes->map(function ($showtime) use ($bookings) {
                $timeKey = Carbon::parse($showtime->start_time)->format('H:i');
                $capacity = $showtime->room ? $showtime->room->seats->count() : 0;

                $bookingsOfShowtime = $bookings->filter(function ($booking) use ($showtime) {
                    return $booking->showtime->id === $showtime->id;
                });

                return [
                    'timeKey'    => $timeKey,
                    'paid'       => $bookingsOfShowtime->where('status', 'paid')->count(),
                    'seatsCount' => $bookingsOfShowtime->where('status', 'paid')->pluck('seats')->flatten()->count(),
                    'failed'     => $bookingsOfShowtime->whereIn('status', ['failed', 'expired'])->count(),
                    'capacity'   => $capacity,
                    'revenue'    => $bookingsOfShowtime->where('status', 'paid')->sum('total_price'),
                ];
            })
                ->groupBy('timeKey')
                ->map(function ($items) {
                    return [
                        'paid'       => $items->sum('paid'),
                        'failed'     => $items->sum('failed'),
                        'seatsCount' => $items->sum('seatsCount'),
                        'capacity'   => $items->sum('capacity'),
                        'revenue'    => $items->sum('revenue'),
                    ];
                })
                ->sortKeys();
        };

        $dataA = $processRange($showtimesA, $bookingChart);

        return [
            'rangeA' => $dataA,
        ];
    }


    public function loadData(?array $filter = null)
    {
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement()
    {
        return "document.getElementById('showtimeChart')";
    }

    protected function buildChartConfig()
    {
        $dataA = $this->data['rangeA'];
        $allKeys = $dataA->keys();

        $mapSeries = function ($data, $keys, $field) {
            return $keys->map(fn($key) => $data->has($key) ? $data[$key][$field] : 0);
        };

        $seatsA = json_encode($mapSeries($dataA, $allKeys, 'seatsCount'));
        $capacity = json_encode($mapSeries($dataA, $allKeys, 'capacity'));
        $paidA = json_encode($mapSeries($dataA, $allKeys, 'paid'));
        $revenueA = json_encode($mapSeries($dataA, $allKeys, 'revenue'));
        $categories = json_encode($allKeys->values());

        return <<<JS
        {
            series: [
                { name: 'Sức chứa', data: $capacity },
                { name: 'Ghế đã bán', data: $seatsA },
            ],
            chart: {
                type: 'bar',
                height: 450,
                background: 'transparent',
                toolbar: { show: true },
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            colors: ['#34A853','#4285F4','#FFB300','#EA4335','#FF7043'],
            plotOptions: { bar: { horizontal: false, columnWidth: '60%', endingShape: 'rounded', borderRadius: 6 } },
            dataLabels: { enabled: false },
            stroke: { show: false },
            xaxis: {
                categories: $categories,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#adb5bd', fontSize: '12px', fontWeight: 600 } }
            },
            yaxis: { min: 0, tickAmount: 7, labels: { style: { colors: '#adb5bd', fontSize: '12px' } } },
            grid: { show: true, borderColor: '#495057', strokeDashArray: 2, xaxis: { lines: { show: false } } },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'left',
                offsetY: -10,
                labels: { colors: '#f8f9fa' },
                markers: { width: 12, height: 12, fillColors: ['#34A853','#4285F4'], radius: 3 }
            },
            tooltip: {
                shared: true,
                intersect: false,
                theme: 'dark',
                custom: function({series, seriesIndex, dataPointIndex, w}) {
                    const times = $categories;
                    const soldA = $seatsA;
                    const paidA = $paidA;
                    const cap = $capacity;
                    const revenueA = $revenueA;

                    const time = times[dataPointIndex];
                    const soldWeekA = soldA[dataPointIndex];
                    const paidWeekA = paidA[dataPointIndex];
                    const capacityVal = cap[dataPointIndex];
                    const percentageA = capacityVal > 0 ? ((soldWeekA / capacityVal) * 100).toFixed(1) : 0;
                    const revenueAFormatted = Number(revenueA[dataPointIndex]).toLocaleString('vi');

                    return `
                        <div style="background:#fff;color:#000;padding:15px;border-radius:10px;box-shadow:0 4px 20px rgba(0,0,0,0.3);min-width:220px;border:1px solid #495057;">
                            <div style="font-weight:600;font-size:14px;margin-bottom:8px;">🎬 Suất \${time}</div>
                            <div style="margin-bottom:6px;">🪑 Sức chứa: \${capacityVal}</div>
                            <div style="margin-bottom:6px;">🎟️ Ghế đã bán: <strong>\${soldWeekA}</strong> | Tỷ lệ lấp đầy: \${percentageA}%</div>
                            <div style="margin-bottom:6px;">🎟️ Vé đã bán: <strong>\${paidWeekA}</strong></div>
                            <div style="margin-bottom:6px;">💵 Doanh thu: <strong>\${revenueAFormatted} ₫</strong></div>
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
