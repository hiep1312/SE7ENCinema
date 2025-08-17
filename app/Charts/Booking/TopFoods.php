<?php

namespace App\Charts\Booking;

use App\Models\FoodOrderItem;
use Illuminate\Support\Facades\DB;

class TopFoods {
    protected $data;

    protected function queryData(?string $filter = null){
        /* Viết truy vấn CSDL tại đây */
        $query = FoodOrderItem::select([
            'food_items.name as food_name',
            DB::raw('SUM(food_order_items.quantity) as total_quantity'),
            DB::raw('SUM(food_order_items.price * food_order_items.quantity) as total_revenue'),
            DB::raw('COUNT(DISTINCT food_order_items.booking_id) as total_bookings')])
            ->join('food_variants', 'food_order_items.food_variant_id', '=', 'food_variants.id')
            ->join('food_items', 'food_variants.food_item_id', '=', 'food_items.id')
            ->join('bookings', 'bookings.id', '=', 'food_order_items.booking_id')
            ->where('bookings.status', 'paid');

        return $query->groupBy('food_items.name')
            ->orderByDesc('total_quantity')
            ->limit(5)->get();
    }

    public function loadData(?string $filter = null){
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement(){
        return "document.getElementById('topFoodsChart')";
    }

    protected function buildChartConfig(){
        /* Viết cấu hình biểu đồ tại đây */
        $topFoodsData = $this->data;
        $foodLabels = $topFoodsData->map(fn($item) => $item->food_name)->toJson();
        $foodQuantities = $topFoodsData->map(fn($item) => $item->total_quantity)->toJson();
        $foodRevenues = $topFoodsData->map(fn($item) => $item->total_revenue)->toJson();
        $RevenueF = $topFoodsData->map(fn($item) => $item->total_bookings)->toJson();
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
            theme: 'dark'
        },
        tooltip: {
            theme: 'dark',
            custom: function({
                series,
                seriesIndex,
                dataPointIndex,
                w
            }) {
                const foodNames = $foodLabels;
                const quantities = $foodQuantities;
                const revenues = $foodRevenues;
                const RevenueF = $RevenueF;

                const tenMon = foodNames[dataPointIndex] || '';
                const soLuong = quantities[dataPointIndex] || 0;
                const doanhThu = revenues[dataPointIndex] || 0;
                

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
                        <span class="text-warning">🛒 Tổng đơn hàng:</span>
                        <span class="fw-bold fs-6 text-warning">\${RevenueF[dataPointIndex]}</span>
                    </div>
                </div>
            `;
            }
        },
        dataLabels: {
            enabled: false
        },
        colors: ['#dc3545', '#fd7e14', '#ffc107', '#28a745', '#17a2b8'],
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
            categories: $foodLabels,
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
        return "updateDataTopMoviesAndRoomsByPeriod";
    }

    public function compileJavascript(){

        $ctxText = "ctxTopMoviesAndRoomsByPeriod";
        $optionsText = "optionsTopMoviesAndRoomsByPeriod";
        $chartText = "chartTopMoviesAndRoomsByPeriod";
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
