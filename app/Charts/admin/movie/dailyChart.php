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
        is_array($filter) && [$fromDate, $rangeDays] = $filter;
        $rangeDays = (int) $rangeDays;

        $fromMain = $fromDate ? Carbon::parse($fromDate)->startOfDay() : null;
        $toMain   = ($fromMain && $rangeDays) ? $fromMain->copy()->addDays($rangeDays)->endOfDay() : null;

        $baseScope = fn($q) => $q->where('movie_id', $this->movie->id);

        $mainBookings = collect();
        if ($fromMain && $toMain) {
            $mainBookings = Booking::whereHas('showtime', $baseScope)
                ->whereBetween('created_at', [$fromMain, $toMain])
                ->with(['showtime.room'])
                ->get();
        }

        $mainBuckets = collect();
        if ($fromMain && $toMain) {
            $cur = $fromMain->copy();
            while ($cur <= $toMain) {
                $label = $cur->format('d/m');
                $mainBuckets->push([
                    'start' => $cur->copy()->startOfDay(),
                    'end'   => $cur->copy()->endOfDay(),
                    'label' => $label
                ]);
                $cur->addDay();
            }
        }

        $mainStat = collect();
        foreach ($mainBuckets as $b) {
            [$start, $end, $label] = [$b['start'], $b['end'], $b['label']];
            $mainIn = $mainBookings->filter(fn($bk) => Carbon::parse($bk->created_at)->between($start, $end));
            $mainStat[$label] = [
                'paid' => $mainIn->where('status', 'paid')->count(),
                'cancelled' => $mainIn->whereIn('status', ['failed', 'expired'])->count(),
                'totalRevenue' => $mainIn->where('status', 'paid')->sum('total_price'),
            ];
        }

        return [
            'bookingStatByDate' => collect($mainStat),
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

        $paidJs = json_encode($paid);
        $cancelledJs = json_encode($cancelled);
        $totalRevenueJs = json_encode($totalRevenue);
        $categoriesJs = json_encode($categories);


        return <<<JS
        {
            series: [
                {
                    name: 'Doanh thu (Khoảng chính)',
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
                        return value;
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

                    const paidStart = paid[dataPointIndex] ?? 0;
                    const cancelledStart = cancelled[dataPointIndex] ?? 0;
                    const revenueStartVal = totalRevenue[dataPointIndex] ?? 0;
                    const revenueStart = revenueStartVal.toLocaleString('vi-VN');
                    const dateLabel = w.globals.labels[dataPointIndex];
                    return `
                        <div style="
                            background: #ffffff;
                            color: #000000;
                            padding: 15px;
                            border-radius: 10px;
                            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                            min-width: 260px;
                        ">
                            <div style="font-weight:600; margin-bottom:8px; font-size:14px;">
                                📅 Ngày \${dateLabel}
                            </div>
                            <div style="display:flex; gap: 10px; margin-bottom:6px;">
                                <span>🎟️ Vé bán:</span>
                                <span><strong>\${paidStart}</strong></span>
                            </div>
                            <div style="display:flex; gap: 10px; margin-bottom:6px;">
                                <span>❌ Vé lỗi:</span>
                                <span><strong>\${cancelledStart}</strong></span>
                            </div>
                            <div style="display:flex; gap: 10px; margin-bottom:6px;">
                                <span>💵 Doanh thu:</span>
                                <span><strong>\${revenueStart}</strong></span>
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
