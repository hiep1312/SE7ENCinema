<?php

namespace App\Charts\dashboard;

use Illuminate\Support\Facades\DB;

class FoodManagementChart {
    protected $data;

    protected function queryData(){
        /* Viết truy vấn CSDL tại đây */
        $startDate = now()->subDays(2)->startOfDay();
        $endDate = now()->endOfDay();
        // Lấy Top 10 món ăn theo doanh thu trong khoảng thời gian, chỉ tính booking paid
        $foodRows = DB::table('food_order_items as foi')
            ->join('bookings as b', 'foi.booking_id', '=', 'b.id')
            ->join('food_variants as fv', 'foi.food_variant_id', '=', 'fv.id')
            ->join('food_items as fi', 'fv.food_item_id', '=', 'fi.id')
            ->where('b.status', 'paid')
            ->whereBetween('b.created_at', [$startDate, $endDate])
            ->groupBy('fi.id', 'fi.name')
            ->select('fi.name', DB::raw('SUM(foi.quantity) as total_qty'), DB::raw('SUM(foi.price) as total_revenue'))
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Lấy Top 10 phim theo doanh thu trong khoảng thời gian
        $movieRows = DB::table('bookings as b')
            ->join('showtimes as s', 'b.showtime_id', '=', 's.id')
            ->join('movies as m', 's.movie_id', '=', 'm.id')
            ->where('b.status', 'paid')
            ->whereBetween('b.created_at', [$startDate, $endDate])
            ->groupBy('m.id', 'm.title')
            ->select('m.title', DB::raw('COUNT(*) as total_tickets'), DB::raw('SUM(b.total_price) as total_revenue'))
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Xử lý dữ liệu đồ ăn
        $foodLabels = [];
        $foodRevenue = [];
        $foodQuantity = [];

        if (!$foodRows->isEmpty()) {
            foreach ($foodRows as $r) {
                $foodLabels[] = $r->name ?? 'N/A';
                $foodRevenue[] = (int) $r->total_revenue;
                $foodQuantity[] = (int) $r->total_qty;
            }
        } else {
            $foodLabels = ['Không có dữ liệu đồ ăn'];
            $foodRevenue = [0];
            $foodQuantity = [0];
        }

        // Xử lý dữ liệu phim
        $movieLabels = [];
        $movieRevenue = [];
        $movieTickets = [];

        if (!$movieRows->isEmpty()) {
            foreach ($movieRows as $r) {
                $movieLabels[] = $r->title ?? 'N/A';
                $movieRevenue[] = (int) $r->total_revenue;
                $movieTickets[] = (int) $r->total_tickets;
            }
        } else {
            $movieLabels = ['Không có dữ liệu phim'];
            $movieRevenue = [0];
            $movieTickets = [0];
        }

        return [
            'labels' => $foodLabels,
            'revenue' => $foodRevenue,
            'quantity' => $foodQuantity,
            'movieLabels' => $movieLabels,
            'movieRevenue' => $movieRevenue,
            'movieTickets' => $movieTickets,
        ];
    }

    public function loadData(){
        $this->data = $this->queryData();
    }

    protected function bindDataToElement(){
        return "document.getElementById('foodManagementChart')";
    }

    protected function buildChartConfig(){
        /* Viết cấu hình biểu đồ tại đây */
        $foodManagementData = json_encode($this->data);

        return <<<JS
        {
            chart: {
                type: 'bar',
                height: 380,
                background: 'transparent',
                toolbar: {
                    show: true
                }
            },
            series: [{
                    name: 'Đồ ăn - Doanh thu (VNĐ)',
                    type: 'column',
                    data: (JSON.parse('$foodManagementData').revenue || [])
                },
                {
                    name: 'Đồ ăn - Số lượng',
                    type: 'line',
                    data: (JSON.parse('$foodManagementData').quantity || [])
                },
                {
                    name: 'Phim - Doanh thu (VNĐ)',
                    type: 'column',
                    data: (JSON.parse('$foodManagementData').movieRevenue || [])
                },
                {
                    name: 'Phim - Số vé',
                    type: 'line',
                    data: (JSON.parse('$foodManagementData').movieTickets || [])
                }
            ],
            xaxis: {
                categories: (JSON.parse('$foodManagementData').labels || []),
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
            yaxis: [{
                    labels: {
                        style: {
                            colors: '#ffffff'
                        },
                        formatter: function(v) { 
                            return new Intl.NumberFormat('vi-VN').format(v);
                        }
                    },
                    title: {
                        text: 'VNĐ',
                        style: {
                            color: '#fff'
                        }
                    }
                },
                {
                    opposite: true,
                    labels: {
                        style: {
                            colors: '#ffffff'
                        },
                        formatter: function(v) { 
                            return Math.floor(v);
                        }
                    },
                    title: {
                        text: 'Số lượng',
                        style: {
                            color: '#fff'
                        }
                    }
                }
            ],
            colors: ['#FF9800', '#4CAF50', '#9C27B0', '#F44336'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: [0, 3, 0, 3],
                curve: 'smooth'
            },
            legend: {
                position: 'top',
                labels: {
                    colors: '#ffffff'
                }
            },
            grid: {
                borderColor: '#2d3748',
                strokeDashArray: 1
            },
            tooltip: {
                theme: 'dark',
                shared: true,
                intersect: false,
                custom: function(opts) {
                    const series = opts.series;
                    const dataPointIndex = opts.dataPointIndex;
                    const w = opts.w;
                    
                    const categories = (w.config.xaxis && w.config.xaxis.categories) || [];
                    const name = categories[dataPointIndex] || '';
                    const foodRevenue = series && series[0] && series[0][dataPointIndex] ? series[0][dataPointIndex] : 0;
                    const foodQuantity = series && series[1] && series[1][dataPointIndex] ? series[1][dataPointIndex] : 0;
                    const movieRevenue = series && series[2] && series[2][dataPointIndex] ? series[2][dataPointIndex] : 0;
                    const movieTickets = series && series[3] && series[3][dataPointIndex] ? series[3][dataPointIndex] : 0;

                    const foodRevenueFmt = new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(foodRevenue);

                    const movieRevenueFmt = new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(movieRevenue);

                    return '<div style="padding:12px;background:rgba(0,0,0,0.95);border-radius:8px;min-width:280px">' +
                            '<div style="font-weight:700;color:#FF9800;margin-bottom:8px">🍔 ' + name + '</div>' +
                            '<div style="border-left:3px solid #FF9800;padding-left:8px;margin-bottom:8px">' +
                                '<div style="color:#FF9800;font-weight:600;margin-bottom:4px">🍽️ Đồ ăn:</div>' +
                                '<div style="display:flex;justify-content:space-between;gap:8px;color:#ddd;margin-bottom:2px">' +
                                '<span>Doanh thu:</span>' +
                                    '<strong style="color:#fff">' + foodRevenueFmt + '</strong>' +
                            '</div>' +
                                '<div style="display:flex;justify-content:space-between;gap:8px;color:#ddd">' +
                                '<span>Số lượng:</span>' +
                                    '<strong style="color:#fff">' + foodQuantity + ' số lượng</strong>' +
                                '</div>' +
                            '</div>' +
                            '<div style="border-left:3px solid #9C27B0;padding-left:8px">' +
                                '<div style="color:#9C27B0;font-weight:600;margin-bottom:4px">🎬 Phim:</div>' +
                                '<div style="display:flex;justify-content:space-between;gap:8px;color:#ddd;margin-bottom:2px">' +
                                    '<span>Doanh thu:</span>' +
                                    '<strong style="color:#fff">' + movieRevenueFmt + '</strong>' +
                                '</div>' +
                                '<div style="display:flex;justify-content:space-between;gap:8px;color:#ddd">' +
                                    '<span>Số vé:</span>' +
                                    '<strong style="color:#fff">' + movieTickets + ' vé</strong>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
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
        return "updateDataFoodManagementChart";
    }

    public function compileJavascript(){
        $ctxText = "ctxFoodManagementChart";
        $optionsText = "optionsFoodManagementChart";
        $chartText = "chartFoodManagementChart";
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