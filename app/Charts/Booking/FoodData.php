<?php

namespace App\Charts\booking;

use App\Models\Booking;
use App\Models\FoodOrderItem;
use Illuminate\Support\Facades\DB;

class FoodData
{
    protected $data;

    protected function queryData()
    {
        $query = FoodOrderItem::select([
            DB::raw('DATE(bookings.created_at) as date'),
            DB::raw('COUNT(DISTINCT bookings.id) as total_bookings'),
            DB::raw('SUM(food_order_items.quantity) as total_food_items'),
            DB::raw('SUM(food_order_items.price * food_order_items.quantity) as total_food_revenue'),
            // Calculate average food items per booking as total_food_items / total_bookings
            DB::raw('SUM(food_order_items.quantity) / COUNT(DISTINCT bookings.id) as avg_food_items_per_booking'),
            DB::raw('AVG(food_order_items.price * food_order_items.quantity) as avg_food_revenue_per_booking')
        ])
            ->join('food_variants', 'food_order_items.food_variant_id', '=', 'food_variants.id')
            ->join('food_items', 'food_variants.food_item_id', '=', 'food_items.id')
            ->join('bookings', 'food_order_items.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'paid')
            ->groupBy(DB::raw('DATE(bookings.created_at)'))
            ->orderBy('date')
            ->limit(30)
            ->get();

        return $query;
    }

    public function loadData(?string $filter = null){
        $this->data = $this->queryData();
    }

    protected function bindDataToElement()
    {
        return "document.getElementById('foodsChart')";
    }

    protected function buildChartConfig()
{
    $foodsData = $this->data;
    $labels = $foodsData->map(fn($item) => $item->date)->toJson();
    $totalFoodItems = $foodsData->map(fn($item) => $item->total_food_items)->toJson();
    $totalFoodRevenue = $foodsData->map(fn($item) => $item->total_food_revenue)->toJson();
    // Ép kiểu số thực để đảm bảo JSON chứa số, không phải chuỗi
    $avgFoodItems = $foodsData->map(fn($item) => (float) $item->avg_food_items_per_booking)->toJson();
    $totalBookings = $foodsData->map(fn($item) => $item->total_bookings)->toJson();

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
                        filename: 'phan-tich-mon-an',
                        columnDelimiter: ',',
                        headerCategory: 'Ngày',
                        headerValue: 'Số lượng',
                        categoryFormatter: function(x) {
                            return x;
                        },
                        valueFormatter: function(y) {
                            return new Intl.NumberFormat('vi-VN').format(y);
                        }
                    },
                    svg: {
                        filename: 'phan-tich-mon-an',
                    },
                    png: {
                        filename: 'phan-tich-mon-an',
                    }
                },
                autoSelected: 'zoom'
            },
            zoom: {
                enabled: false
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
                const dates = $labels;
                const foodItems = $totalFoodItems;
                const foodRevenue = $totalFoodRevenue;
                const avgItems = $avgFoodItems;
                const bookings = $totalBookings;

                const ngay = dates[dataPointIndex] || '';
                const tongMon = foodItems[dataPointIndex] || 0;
                const doanhThu = foodRevenue[dataPointIndex] || 0;
                const donHang = bookings[dataPointIndex] || 0;
                // Ép kiểu số thực và xử lý trường hợp không hợp lệ
                const tbMon = parseFloat(avgItems[dataPointIndex]) || 0;

                return `
                <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 320px;">
                    <div class="d-flex align-items-center mb-3">
                        <span class="fs-5 me-2">📅</span>
                        <h6 class="mb-0 text-white fw-bold">\${ngay}</h6>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-warning">🍽️ Tổng món ăn:</span>
                        <span class="fw-bold fs-6 text-warning">\${tongMon}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-info">💰 Doanh thu:</span>
                        <span class="fw-bold fs-6 text-info">\${new Intl.NumberFormat('vi-VN').format(doanhThu)}đ</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-white">🛒 Số đơn hàng:</span>
                        <span class="fw-bold fs-6 text-white">\${donHang}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-success">📊 TB món/đơn:</span>
                        <span class="fw-bold fs-6 text-success">\${isNaN(tbMon) ? '0.0' : tbMon.toFixed(1)}</span>
                    </div>
                </div>
                `;
            }
        },
        dataLabels: {
            enabled: false
        },
        colors: ['#fd7e14', '#e83e8c', '#6f42c1', '#20c997'],
        series: [{
                name: 'Tổng món ăn',
                type: 'bar',
                data: $totalFoodItems
            },
            {
                name: 'Doanh thu món ăn',
                type: 'line',
                data: $totalFoodRevenue
            },
            {
                name: 'TB món/đơn',
                type: 'area',
                data: $avgFoodItems
            }
        ],
        fill: {
            type: ['solid', 'solid', 'gradient'],
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.9,
                stops: [0, 90, 100],
                colorStops: [
                    [],
                    [],
                    [{
                            offset: 0,
                            color: '#20c997',
                            opacity: 0.7
                        },
                        {
                            offset: 100,
                            color: '#23272b',
                            opacity: 0.1
                        }
                    ]
                ]
            }
        },
        stroke: {
            width: [0, 4, 0],
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
                    fontSize: '12px'
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
            },
            tickPlacement: 'on'
        },
        yaxis: [{
                title: {
                    text: 'Số lượng',
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
            },
            {
                opposite: true,
                title: {
                    text: 'Doanh thu (VND)',
                    style: {
                        color: '#e83e8c'
                    }
                },
                labels: {
                    style: {
                        colors: '#e83e8c',
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
        return "updateDataFoodData";
    }

    public function compileJavascript()
    {
        $ctxText = "ctxFoodData";
        $optionsText = "optionsFoodData";
        $chartText = "chartFoodData";
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