<?php

namespace App\Charts\dashboard;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class RevenueSourceChart {
    protected $data;

    protected function queryData(){
        /* Viết truy vấn CSDL tại đây */
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();
        
        // Sử dụng định dạng theo ngày
        $groupFormat = '%Y-%m-%d';
        $labelFormat = 'd/m';
        $keyFormatPhp = 'Y-m-d';

        // Doanh thu vé theo mốc thời gian
        $ticketMap = Booking::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw("DATE_FORMAT(created_at, '$groupFormat') as period"), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('period')
            ->pluck('revenue', 'period')
            ->toArray();

        // Doanh thu đồ ăn theo mốc thời gian (join với bookings để dùng created_at của booking)
        $foodMap = DB::table('food_order_items')
            ->join('bookings', 'food_order_items.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'paid')
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->select(DB::raw("DATE_FORMAT(bookings.created_at, '$groupFormat') as period"), DB::raw('SUM(food_order_items.price) as revenue'))
            ->groupBy('period')
            ->pluck('revenue', 'period')
            ->toArray();

        // Tạo dãy labels liên tục theo ngày/tháng
        $labels = [];
        $ticketSeries = [];
        $foodSeries = [];

        $cursor = $startDate->copy()->startOfDay();
        while ($cursor->lte($endDate)) {
            $key = $cursor->format($keyFormatPhp);
            $labels[] = $cursor->format($labelFormat);
            $ticketSeries[] = (int)($ticketMap[$key] ?? 0);
            $foodSeries[] = (int)($foodMap[$key] ?? 0);

            $cursor->addDay()->startOfDay();
        }

        // Nếu tất cả đều 0 thì trả về placeholder tránh lỗi chart rỗng
        if (array_sum($ticketSeries) === 0 && array_sum($foodSeries) === 0) {
            return [
                'labels' => [now()->format('d/m')],
                'series' => [
                    ['name' => 'Vé', 'data' => [0]],
                    ['name' => 'Đồ ăn', 'data' => [0]],
                ]
            ];
        }

        return [
            'labels' => $labels,
            'series' => [
                ['name' => 'Vé', 'data' => $ticketSeries],
                ['name' => 'Đồ ăn', 'data' => $foodSeries],
            ]
        ];
    }

    public function loadData(){
        $this->data = $this->queryData();
    }

    protected function bindDataToElement(){
        return "document.getElementById('revenueSourceChart')";
    }

    protected function buildChartConfig(){
        /* Viết cấu hình biểu đồ tại đây */
        $revenueSourceData = json_encode($this->data);
        
        return <<<JS
        {
            chart: {
                type: 'area',
                height: 300,
                background: 'transparent',
                toolbar: {
                    show: true
                }
            },
            series: (JSON.parse('$revenueSourceData').series || []),
            xaxis: {
                categories: (JSON.parse('$revenueSourceData').labels || []),
                labels: {
                    style: {
                        colors: '#ffffff',
                        fontSize: '12px'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#ffffff',
                        fontSize: '12px'
                    },
                    formatter: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value);
                    }
                }
            },
            colors: ['#17A2B8', '#20C997'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'vertical',
                    shadeIntensity: 0.3,
                    gradientToColors: ['#0EA5A8', '#16A34A'],
                    inverseColors: false,
                    opacityFrom: 0.5,
                    opacityTo: 0.2,
                    stops: [0, 100]
                }
            },
            legend: {
                position: 'top',
                labels: {
                    colors: '#ffffff'
                }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function(value) {
                        return new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(value);
                    }
                }
            }
        }
        JS;
    }

    public function getFilterText(string $filterValue){
        return match ($filterValue){
            default => "N/A"
        };
    }

    public function getChartConfig(){
        return $this->buildChartConfig();
    }

    public function getData(){
        return $this->data;
    }

    public function getEventName(){
        return "updateDataRevenueSourceChart";
    }

    public function compileJavascript(){
        $ctxText = "ctxRevenueSourceChart";
        $optionsText = "optionsRevenueSourceChart";
        $chartText = "chartRevenueSourceChart";
        echo <<<JS
        const {$ctxText} = {$this->bindDataToElement()};
        window.{$optionsText} = {$this->buildChartConfig()};

        window.{$chartText} = createScChart({$ctxText}, {$optionsText});

        Livewire.on("{$this->getEventName()}", function ([data]){
            window.{$optionsText} = new Function("return " + data)();
            if(window.{$chartText}) window.{$chartText}.updateOptions(window.{$optionsText});
        });
        JS;
    }
}