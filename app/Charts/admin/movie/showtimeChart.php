<?php

namespace App\Charts\admin\movie;

use App\Models\Booking;
use Carbon\Carbon;

use function PHPUnit\Framework\isArray;

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
        is_array($filter) && [$fromDate, $rangeDays, $compareDate,$rangeUnit] = $filter;
        $rangeDays = (int) $rangeDays;
        /* Viết truy vấn CSDL tại đây */
        $fromDate = Carbon::parse($fromDate)->startOfDay();
        $toDate = (clone $fromDate)->add($rangeUnit,$rangeDays)->endOfDay();
        
        $compareFromDate = Carbon::parse($compareDate)->startOfDay();
        $compareToDate = (clone $compareFromDate)->add($rangeUnit,$rangeDays)->endOfDay();

        // Query tất cả bookings có showtime thuộc phim
        $bookingChart = Booking::whereHas('showtime', function ($q) {
            $q->where('movie_id', $this->movie->id);
        })
            ->with(['showtime.room'])
            ->get();

        // Hàm xử lý chung cho 1 khoảng ngày
        $processRange = function ($bookings, $from, $to) {
            $showtimes = $bookings->pluck('showtime')
                ->filter(function ($showtime) use ($from, $to) {
                    return Carbon::parse($showtime->start_time)->between($from, $to);
                })
                ->unique()
                ->values();

            $bookingCountFormatted = $showtimes
                ->filter(fn($showtime) => $showtime->room)
                ->map(function ($showtime) use ($bookings) {
                    $timeKey = Carbon::parse($showtime->start_time)->format('H:i');
                    $capacity = $showtime->room->seats->count();
                    $bookingsOfShowtime = $bookings->filter(function ($booking) use ($showtime) {
                        return $booking->showtime->id === $showtime->id;
                    });
                    return [
                        'timeKey'   => $timeKey,
                        'paid'      => $bookingsOfShowtime->where('status', 'paid')->count(),
                        'seatsCount' => $bookingsOfShowtime->where('status', 'paid')->pluck('seats')->flatten()->count(),
                        'failed'    => $bookingsOfShowtime->whereIn('status', ['failed', 'expired'])->count(),
                        'capacity'  => $capacity,
                        'revenue'   => $bookingsOfShowtime->where('status', 'paid')->sum('total_price'),
                    ];
                })
                ->groupBy('timeKey')
                ->map(function ($items) {
                    return [
                        'paid'      => $items->sum('paid'),
                        'failed'    => $items->sum('failed'),
                        'seatsCount' => $items->sum('seatsCount'),
                        'capacity'  => $items->sum('capacity'),
                        'revenue'   => $items->sum('revenue'),
                    ];
                })
                ->sortKeys();

            return $bookingCountFormatted;
        };

        $dataA = $processRange($bookingChart, $fromDate, $toDate);
        if ($compareDate != null) {
            $dataB = $processRange($bookingChart, $compareFromDate, $compareToDate);
        } else {
            $dataB = null;
        }

        return [
            'rangeA' => $dataA,
            'rangeB' => $dataB,
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
        $dataB = $this->data['rangeB'];
        $allKeys = $dataA->keys()->merge($dataB ? $dataB->keys() : collect())->unique()->sort()->values();

        // Map lại dữ liệu A và B theo categories chung
        $mapSeries = function ($data, $keys) {
            return $keys->map(fn($key) => $data->has($key) ? $data[$key]['seatsCount'] : 0);
        };

        $seatsA = json_encode($mapSeries($dataA, $allKeys));
        $seatsB = json_encode($dataB ? $mapSeries($dataB, $allKeys) : $allKeys->map(fn() => 0));

        $capacity = json_encode($allKeys->map(fn($key) => $dataA->has($key) ? $dataA[$key]['capacity'] : ($dataB && $dataB->has($key) ? $dataB[$key]['capacity'] : 0)));
        $paidA = json_encode($allKeys->map(fn($key) => $dataA->has($key) ? $dataA[$key]['paid'] : 0));
        $paidB = json_encode($allKeys->map(fn($key) => $dataB && $dataB->has($key) ? $dataB[$key]['paid'] : 0));
        $revenueA = json_encode($allKeys->map(fn($key) => $dataA->has($key) ? $dataA[$key]['revenue'] : 0));
        $revenueB = json_encode($allKeys->map(fn($key) => $dataB && $dataB->has($key) ? $dataB[$key]['revenue'] : 0));

        $categories = json_encode($allKeys);

        return <<<JS
        {
            series: [
                { name: 'Sức chứa', data: $capacity },
                { name: 'Ghế đã bán (Giá trị 1)', data: $seatsA },
                { name: 'Ghế đã bán (Giá trị 2)', data: $seatsB },
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
                markers: { width: 12, height: 12, fillColors: ['#34A853','#4285F4','#FFB300','#EA4335','#FF7043'], radius: 3 }
            },
            tooltip: {
                shared: true,
                intersect: false,
                theme: 'dark',
                custom: function({series, seriesIndex, dataPointIndex, w}) {
                    const times = $categories;
                    const soldA = $seatsA;
                    const soldB = $seatsB;
                    const paidA = $paidA;
                    const paidB = $paidB;
                    const cap = $capacity;
                    const revenueA = $revenueA;
                    const revenueB = $revenueB;

                    const time = times[dataPointIndex];
                    const soldWeekA = soldA[dataPointIndex];
                    const soldWeekB = soldB[dataPointIndex];
                    const paidWeekA = paidA[dataPointIndex];
                    const paidWeekB = paidB[dataPointIndex];
                    const capacityVal = cap[dataPointIndex];
                    const percentageA = capacityVal > 0 ? ((soldWeekA / capacityVal) * 100).toFixed(1) : 0;
                    const percentageB = capacityVal > 0 ? ((soldWeekB / capacityVal) * 100).toFixed(1) : 0;
                    const revenueAFormatted = Number(revenueA[dataPointIndex]).toLocaleString('vi');
                    const revenueBFormatted = Number(revenueB[dataPointIndex]).toLocaleString('vi');

                    return `
                        <div style="background:#fff;color:#000;padding:15px;border-radius:10px;box-shadow:0 4px 20px rgba(0,0,0,0.3);min-width:220px;border:1px solid #495057;">
                            <div style="font-weight:600;font-size:14px;margin-bottom:8px;">🎬 Suất \${time}</div>
                            <div style="margin-bottom:6px;">🪑 Sức chứa: \${capacityVal}</div>
                            <div style="margin-bottom:6px;">🎟️ Ghế đã bán (Giá trị 1): <strong>\${soldWeekA}</strong> | Tỷ lệ lấp đầy: \${percentageA}%</div>
                            <div style="margin-bottom:6px;">🎟️ Ghế đã bán (Giá trị 2): <strong>\${soldWeekB}</strong> | Tỷ lệ lấp đầy: \${percentageB}%</div>
                            <div style="margin-bottom:6px;">🎟️ Vé đã bán (Giá trị 1): <strong>\${paidWeekA}</strong></div>
                            <div style="margin-bottom:6px;">🎟️ Vé đã bán (Giá trị 2): <strong>\${paidWeekB}</strong></div>
                            <div style="margin-bottom:6px;">💵 Doanh thu (Giá trị 1): <strong>\${revenueAFormatted} ₫</strong></div>
                            <div style="margin-bottom:6px;">💵 Doanh thu (Giá trị 2): <strong>\${revenueBFormatted} ₫</strong></div>
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
