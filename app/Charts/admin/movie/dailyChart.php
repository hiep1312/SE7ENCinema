<?php

namespace App\Charts\admin\movie;

use App\Models\Booking;
use Carbon\Carbon;

class dailyChart
{
    protected $data;
    protected $movie;
    public function __construct($movie)
    {
        $this->movie = $movie;
    }

    protected function queryData(?array $filter = null)
    {
        is_array($filter) && [$fromDate, $rangeDays, $compareDate, $rangeUnit] = $filter;
        $rangeDays = (int) $rangeDays;

        $fromMain = $fromDate ? Carbon::parse($fromDate)->startOfDay() : null;
        $toMain   = ($fromMain && $rangeDays) ? $fromMain->copy()->add($rangeUnit, $rangeDays)->endOfDay() : null;

        $fromCmp = $compareDate ? Carbon::parse($compareDate)->startOfDay() : null;
        $toCmp   = ($fromCmp && $rangeDays) ? $fromCmp->copy()->add($rangeUnit, $rangeDays)->endOfDay() : null;

        if (!($fromMain && $toMain) && !($fromCmp && $toCmp)) {
            return ['bookingStatByDate' => collect(), 'compareStatByDate' => collect()];
        }

        $baseScope = fn($q) => $q->where('movie_id', $this->movie->id);

        $mainQuery = Booking::whereHas('showtime', $baseScope)->with(['showtime.room']);
        if ($fromMain && $toMain) {
            $mainQuery->whereHas('showtime', function ($q) use ($fromMain, $toMain) {
                $q->whereBetween('start_time', [$fromMain, $toMain]);
            });
        }
        $mainBookings = $mainQuery->get();

        $cmpBookings = collect();
        if ($fromCmp && $toCmp) {
            $cmpBookings = Booking::whereHas('showtime', $baseScope)
                ->whereHas('showtime', function ($q) use ($fromCmp, $toCmp) {
                    $q->whereBetween('start_time', [$fromCmp, $toCmp]);
                })
                ->with(['showtime.room'])
                ->get();
        }
        switch ($rangeUnit) {
            case 'years':
                $totalDays = $rangeDays * 365;
                break;
            case 'months':
                $totalDays = $rangeDays *30;
                break;
            default:
                $totalDays = $rangeDays;
        }
        
        $groupType = 'daily';
        if ($totalDays > 365) $groupType = 'yearly';
        elseif ($totalDays > 90) $groupType = 'monthly';
        elseif ($totalDays > 30) $groupType = 'weekly';

        // ======= UNION RANGE (bao phủ cả main lẫn compare) =======
        // Nếu một bên null, dùng bên còn lại
        $unionStart = collect([$fromMain, $fromCmp])->filter()->min();
        $unionEnd   = collect([$toMain, $toCmp])->filter()->max();

        // Tạo các bucket theo groupType
        $buckets = collect();
        $cur = $unionStart->copy();

        while ($cur <= $unionEnd) {
            if ($groupType === 'daily') {
                $start = $cur->copy()->startOfDay();
                $end   = $cur->copy()->endOfDay();
                $label = $cur->format('d/m'); // hiển thị
                $buckets->push(compact('start', 'end', 'label'));
                $cur->addDay();
            } elseif ($groupType === 'weekly') {
                $start = $cur->copy()->startOfWeek();
                $end   = $cur->copy()->endOfWeek();
                $label = $start->format('d/m') . ' - ' . $end->format('d/m');
                $buckets->push(compact('start', 'end', 'label'));
                $cur = $end->copy()->addDay();
            } elseif ($groupType === 'monthly') {
                $start = $cur->copy()->startOfMonth();
                $end   = $cur->copy()->endOfMonth();
                $label = $start->format('m-Y');
                $buckets->push(compact('start', 'end', 'label'));
                $cur->addMonth();
            } else { // yearly
                $start = $cur->copy()->startOfYear();
                $end   = $cur->copy()->endOfYear();
                $label = $start->format('Y');
                $buckets->push(compact('start', 'end', 'label'));
                $cur->addYear();
            }
        }

        // Tính thống kê cho từng bucket cho MAIN & COMPARE, cùng nhãn
        $mainStat = collect();
        $cmpStat  = collect();

        foreach ($buckets as $b) {
            [$start, $end, $label] = [$b['start'], $b['end'], $b['label']];

            $mainIn = $mainBookings->filter(function ($bk) use ($start, $end) {
                $t = Carbon::parse($bk->showtime->start_time);
                return $t->between($start, $end);
            });
            $cmpIn = $cmpBookings->filter(function ($bk) use ($start, $end) {
                $t = Carbon::parse($bk->showtime->start_time);
                return $t->between($start, $end);
            });

            $mainStat[$label] = [
                'paid'         => $mainIn->where('status', 'paid')->count(),
                'cancelled'    => $mainIn->whereIn('status', ['failed', 'expired'])->count(),
                'totalRevenue' => $mainIn->where('status', 'paid')->sum('total_price'),
            ];
            $cmpStat[$label] = [
                'paid'         => $cmpIn->where('status', 'paid')->count(),
                'cancelled'    => $cmpIn->whereIn('status', ['failed', 'expired'])->count(),
                'totalRevenue' => $cmpIn->where('status', 'paid')->sum('total_price'),
            ];
        }

        return [
            'bookingStatByDate' => collect($mainStat),
            'compareStatByDate' => collect($cmpStat),
        ];
    }



    public function loadData(?array $filter = null)
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

        // dữ liệu ngày so sánh
        $paidCompare = $this->data['compareStatByDate']->pluck('paid')->toArray();
        $cancelledCompare = $this->data['compareStatByDate']->pluck('cancelled')->toArray();
        $totalRevenueCompare = $this->data['compareStatByDate']->pluck('totalRevenue')->toArray();

        $paidJs = json_encode($paid);
        $cancelledJs = json_encode($cancelled);
        $totalRevenueJs = json_encode($totalRevenue);
        $categoriesJs = json_encode($categories);

        $paidCompareJs = json_encode($paidCompare);
        $cancelledCompareJs = json_encode($cancelledCompare);
        $totalRevenueCompareJs = json_encode($totalRevenueCompare);

        return <<<JS
        {
            series: [
                {
                    name: 'Dữ liệu ngày bắt đầu',
                    data: $totalRevenueJs
                },
                {
                    name: 'Dữ liệu ngày so sánh',
                    data: $totalRevenueCompareJs
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
                        colors: '#adb5bd',
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
                shared: true,
                custom: function({series, dataPointIndex, w}) {
                    const paid = $paidJs;
                    const cancelled = $cancelledJs;
                    const totalRevenue = $totalRevenueJs;

                    const paidCompare = $paidCompareJs;
                    const cancelledCompare = $cancelledCompareJs;
                    const totalRevenueCompare = $totalRevenueCompareJs;

                    // dữ liệu ngày bắt đầu
                    const paidStart = paid[dataPointIndex] ?? 0;
                    const cancelledStart = cancelled[dataPointIndex] ?? 0;
                    const revenueStart = (totalRevenue[dataPointIndex] ?? 0).toLocaleString('vi-VN');

                    // dữ liệu ngày so sánh
                    const paidC = paidCompare[dataPointIndex] ?? 0;
                    const cancelledC = cancelledCompare[dataPointIndex] ?? 0;
                    const revenueC = (totalRevenueCompare[dataPointIndex] ?? 0).toLocaleString('vi-VN');

                    const dateLabel = w.globals.labels[dataPointIndex];

                    return `
                        <div style="
                            background: #ffffff;
                            color: #000000;
                            padding: 15px;
                            border-radius: 10px;
                            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                            min-width: 240px;
                        ">
                            <div style="font-weight:600; margin-bottom:8px; font-size:14px;">
                                📅 Ngày \${dateLabel}
                            </div>

                            <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                                <span>🎟️ Vé bán:</span>
                                <span><strong>\${paidStart}</strong> ↔ <strong>\${paidC}</strong></span>
                            </div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                                <span>❌ Vé lỗi:</span>
                                <span><strong>\${cancelledStart}</strong> ↔ <strong>\${cancelledC}</strong></span>
                            </div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                                <span>💵 Doanh thu:</span>
                                <span><strong>\${revenueStart}</strong> ↔ <strong>\${revenueC}</strong></span>
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
