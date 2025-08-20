<?php

namespace App\Charts\dashboard;

use App\Models\FoodOrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TopFoodsChart {
    protected $data;

    protected function queryData(?string $filter = null){
        $startDate = now()->subDays(30)->startOfDay();
        $endDate = now()->endOfDay();
        
        $query = FoodOrderItem::select([
            'food_items.name as original_name',
            DB::raw('SUBSTRING(food_items.name, 1, 20) as food_name'),
            DB::raw('SUM(food_order_items.quantity) as total_quantity'),
            DB::raw('SUM(food_order_items.price * food_order_items.quantity) as total_revenue'),
            DB::raw('COUNT(DISTINCT food_order_items.booking_id) as total_bookings')
        ])
            ->join('food_variants', 'food_order_items.food_variant_id', '=', 'food_variants.id')
            ->join('food_items', 'food_variants.food_item_id', '=', 'food_items.id')
            ->join('bookings', 'food_order_items.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'paid')
            ->whereBetween('bookings.created_at', [$startDate, $endDate]);

        return $query->groupBy('food_items.name')
            ->orderByDesc('total_quantity')
            ->limit(8)
            ->get();
    }

    public function loadData(?string $filter = null){
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement(){
        return "document.getElementById('topFoodsChart')";
    }

    protected function buildChartConfig(){
        $topFoodsData = $this->data;
        $labels = $topFoodsData->map(fn($item) => $item->food_name)->toJson();
        $foodQuantities = $topFoodsData->map(fn($item) => $item->total_quantity)->toJson();
        $foodRevenues = $topFoodsData->map(fn($item) => $item->total_revenue)->toJson();
        $RevenueF = $topFoodsData->map(fn($item) => $item->total_bookings)->toJson();
        
        // Lấy tên đồ ăn đầy đủ cho tooltip
        $fullNames = $topFoodsData->map(fn($item) => $item->original_name)->toJson();

        return <<<JS
        {
            chart: {
                height: 400,
                type: 'bar',
                background: 'transparent',
                toolbar: {
                    show: true,
                    offsetX: 0,
                    offsetY: 0,
                    tools: {
                        download: true,
                        selection: true,
                        pan: true,
                        reset: true
                    },
                    export: {
                        csv: {
                            filename: 'top-mon-an',
                            columnDelimiter: ',',
                            headerCategory: 'Món ăn',
                            headerValue: 'Số lượng',
                            categoryFormatter: function(x) {
                                return x;
                            },
                            valueFormatter: function(y) {
                                return new Intl.NumberFormat('vi-VN').format(y);
                            }
                        },
                        svg: {
                            filename: 'top-mon-an',
                        },
                        png: {
                            filename: 'top-mon-an',
                        }
                    },
                    autoSelected: 'zoom'
                },
                zoom: {
                    enabled: false,
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            tooltip: {
                theme: 'dark',
                custom: function({
                    series,
                    seriesIndex,
                    dataPointIndex,
                    w
                }) {
                    const foodNames = $labels;
                    const quantities = $foodQuantities;
                    const revenues = $foodRevenues;
                    const bookings = $RevenueF;
                    const fullNames = $fullNames;

                    const tenMon = fullNames[dataPointIndex] || '';
                    const soLuong = quantities[dataPointIndex] || 0;
                    const doanhThu = revenues[dataPointIndex] || 0;
                    const donHang = bookings[dataPointIndex] || 0;

                    return `
                    <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 320px;">
                        <div class="d-flex align-items-center mb-3">
                            <span class="fs-5 me-2">🍽️</span>
                            <h6 class="mb-0 text-white fw-bold">\${tenMon}</h6>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-danger">📦 Số lượng bán:</span>
                            <span class="fw-bold fs-6 text-danger">\${soLuong}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-warning">💰 Doanh thu:</span>
                            <span class="fw-bold fs-6 text-warning">\${new Intl.NumberFormat('vi-VN').format(doanhThu)}đ</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-info">🛒 Đơn hàng:</span>
                            <span class="fw-bold fs-6 text-info">\${donHang}</span>
                        </div>
                    </div>
                    `;
                }
            },
            dataLabels: {
                enabled: false
            },
            colors: ['#dc3545', '#fd7e14', '#ffc107', '#28a745', '#17a2b8', '#6f42c1', '#e83e8c', '#20c997'],
            series: [{
                    name: 'Số lượng bán',
                    type: 'bar',
                    data: $foodQuantities
                },
                {
                    name: 'Doanh thu',
                    type: 'line',
                    data: $foodRevenues
                }
            ],
            stroke: {
                width: [0, 4],
                curve: 'smooth'
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    endingShape: 'rounded',
                    borderRadius: 4
                }
            },
            xaxis: {
                categories: $labels,
                labels: {
                    style: {
                        colors: '#ffffff',
                        fontSize: '11px'
                    },
                    rotate: -45,
                    rotateAlways: false,
                    maxHeight: 60
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: [{
                    title: {
                        text: 'Số lượng',
                        style: {
                            color: '#dc3545'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#dc3545',
                            fontSize: '12px'
                        },
                        formatter: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value);
                        }
                    }
                },
                {
                    opposite: true,
                    title: {
                        text: 'Doanh thu (VND)',
                        style: {
                            color: '#fd7e14'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#fd7e14',
                            fontSize: '12px'
                        },
                        formatter: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value);
                        }
                    }
                }
            ],
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                labels: {
                    colors: '#ffffff'
                }
            },
            grid: {
                show: true,
                borderColor: '#2d3748',
                strokeDashArray: 0,
                position: 'back'
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
        return "updateDataTopFoodsChart";
    }

    public function compileJavascript(){
        $ctxText = "ctxTopFoodsChart";
        $optionsText = "optionsTopFoodsChart";
        $chartText = "chartTopFoodsChart";
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
