<?php

namespace App\Charts\dashboard;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueChart
{
    protected $data;

    protected function queryData(?array $filter = null)
    {
        is_array($filter) && [$fromDate, $rangeDays] = $filter;
        $rangeDays = (int) $rangeDays;
        $fromDate = $fromDate ? Carbon::parse($fromDate) : Carbon::now()->subDays($rangeDays);
        $toDate = $fromDate->copy()->addDays($rangeDays);

        $data = [];

        $currentDate = $fromDate->copy();
        while ($currentDate->lte($toDate)) {
            $revenue = Booking::where('status', 'paid')
                ->whereDate('created_at', $currentDate)
                ->sum('total_price');

            $bookings = Booking::where('status', 'paid')
                ->whereDate('created_at', $currentDate)
                ->count();

            // Lấy thông tin về đồ ăn
            $foodRevenue = DB::table('food_order_items as foi')
                ->join('bookings as b', 'foi.booking_id', '=', 'b.id')
                ->where('b.status', 'paid')
                ->whereDate('b.created_at', $currentDate)
                ->sum(DB::raw('foi.price * foi.quantity'));

            $foodOrders = DB::table('food_order_items as foi')
                ->join('bookings as b', 'foi.booking_id', '=', 'b.id')
                ->where('b.status', 'paid')
                ->whereDate('b.created_at', $currentDate)
                ->count();

            // Lấy thông tin về phim
            $movieRevenue = $revenue - $foodRevenue;
            $movieBookings = DB::table('bookings as b')
                ->join('showtimes as s', 'b.showtime_id', '=', 's.id')
                ->where('b.status', 'paid')
                ->whereDate('b.created_at', $currentDate)
                ->count();

            $data[] = [
                'x' => $currentDate->format('d/m/Y'),
                'y' => $revenue,
                'bookings' => $bookings,
                'foodRevenue' => $foodRevenue,
                'foodOrders' => $foodOrders,
                'movieRevenue' => $movieRevenue,
                'movieBookings' => $movieBookings
            ];
            $currentDate->addDay();
        }
        return $data;
    }

    public function loadData(?array $filter = null)
    {
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement()
    {
        return "document.getElementById('revenueChart')";
    }

    protected function buildChartConfig()
    {
        if (empty($this->data)) {
            $this->loadData();
        }
        $chartData = collect($this->data);
        $labels = $chartData->map(fn($item) => $item['x'])->toJson();
        $revenues = $chartData->map(fn($item) => $item['y'])->toJson();
        $bookings = $chartData->map(fn($item) => $item['bookings'])->toJson();
        $foodRevenues = $chartData->map(fn($item) => $item['foodRevenue'])->toJson();
        $foodOrders = $chartData->map(fn($item) => $item['foodOrders'])->toJson();
        $movieRevenues = $chartData->map(fn($item) => $item['movieRevenue'])->toJson();
        $movieBookings = $chartData->map(fn($item) => $item['movieBookings'])->toJson();

        return <<<JS
    {
        series: [{
            name: 'Doanh thu (VNĐ)',
            data: {$revenues}
        }],
        chart: {
            type: 'area',
            height: 450,
            background: 'transparent',
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                    reset: true
                },
                export: {
                    csv: {
                        filename: 'doanh-thu-se7en-cinema',
                        columnDelimiter: ',',
                        headerCategory: 'Thời gian',
                        headerValue: 'Doanh thu (VNĐ)',
                    },
                    svg: {
                        filename: 'doanh-thu-se7en-cinema',
                    },
                    png: {
                        filename: 'doanh-thu-se7en-cinema',
                    }
                }
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
            },
            zoom: {
                enabled: false,
                type: 'x',
                autoScaleYaxis: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        colors: ['#007bff'],
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                type: 'vertical',
                shadeIntensity: 0.3,
                gradientToColors: ['#0056b3'],
                inverseColors: false,
                opacityFrom: 0.9,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: {$labels},
            labels: {
                style: {
                    colors: '#e2e8f0',
                    fontSize: '12px',
                    fontWeight: 500
                },
                rotate: -45,
                rotateAlways: false
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            title: {
                text: 'Thời gian',
                style: {
                    color: '#e2e8f0',
                    fontSize: '14px',
                    fontWeight: 600
                }
            }
        },
        yaxis: {
            title: {
                text: 'Doanh thu (VNĐ)',
                style: {
                    color: '#e2e8f0',
                    fontSize: '14px',
                    fontWeight: 600
                }
            },
            labels: {
                style: {
                    colors: '#e2e8f0',
                    fontSize: '12px',
                    fontWeight: 500
                },
                formatter: function(value) {
                    return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                }
            }
        },
        grid: {
            show: true,
            borderColor: '#2d3748',
            strokeDashArray: 1,
            xaxis: {
                lines: {
                    show: false
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            },
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            labels: {
                colors: '#e2e8f0'
            },
            markers: {
                width: 12,
                height: 12,
                radius: 6
            }
        },
        tooltip: {
            theme: 'dark',
            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                const labels = {$labels};
                const revenues = {$revenues};
                const bookings = {$bookings};
                const foodRevenues = {$foodRevenues};
                const foodOrders = {$foodOrders};
                const movieRevenues = {$movieRevenues};
                const movieBookings = {$movieBookings};

                const x = labels[dataPointIndex] || '';
                const revenue = revenues[dataPointIndex] || 0;
                const booking = bookings[dataPointIndex] || 0;
                const foodRevenue = foodRevenues[dataPointIndex] || 0;
                const foodOrder = foodOrders[dataPointIndex] || 0;
                const movieRevenue = movieRevenues[dataPointIndex] || 0;
                const movieBooking = movieBookings[dataPointIndex] || 0;

                let phanTramTang = 0;
                let iconTangTruong = '📊';
                let mauTangTruong = '#6c757d';
                if (dataPointIndex > 0) {
                    const doanhThuTruoc = w.config.series[0].data[dataPointIndex - 1].y || 0;
                    if (doanhThuTruoc > 0) {
                        phanTramTang = ((revenue - doanhThuTruoc) / doanhThuTruoc * 100).toFixed(1);
                        if (phanTramTang > 0) {
                            iconTangTruong = '📈';
                            mauTangTruong = '#28a745';
                        } else if (phanTramTang < 0) {
                            iconTangTruong = '📉';
                            mauTangTruong = '#dc3545';
                        }
                    }
                }
                return `
                    <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 400px; max-width: 500px;">
                        <div class="d-flex align-items-center mb-3">
                            <span class="fs-4 me-2">💰</span>
                            <h6 class="mb-0 text-white fw-bold">\${x}</h6>
                        </div>
                        
                        <!-- Tổng quan -->
                        <div class="row g-2 mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-primary bg-opacity-10 rounded">
                                    <span class="text-primary fw-medium">💸 Tổng doanh thu:</span>
                                    <span class="fw-bold fs-6 text-primary">\${new Intl.NumberFormat('vi-VN').format(revenue)}đ</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-success bg-opacity-10 rounded">
                                    <span class="text-success fw-medium">🛒 Tổng đơn hàng:</span>
                                    <span class="fw-bold text-success">\${booking}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-info bg-opacity-10 rounded">
                                    <span class="text-info fw-medium">✅ TB/đơn:</span>
                                    <span class="fw-bold text-info">\${new Intl.NumberFormat('vi-VN').format(revenue / (booking || 1))}đ</span>
                                </div>
                            </div>
                        </div>

                        <!-- Chi tiết doanh thu -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-warning bg-opacity-10 rounded">
                                    <span class="text-warning fw-medium">🎬 Phim:</span>
                                    <span class="fw-bold text-warning">\${new Intl.NumberFormat('vi-VN').format(movieRevenue)}đ</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-danger bg-opacity-10 rounded">
                                    <span class="text-danger fw-medium">🍽️ Đồ ăn:</span>
                                    <span class="fw-bold text-danger">\${new Intl.NumberFormat('vi-VN').format(foodRevenue)}đ</span>
                                </div>
                            </div>
                        </div>

                        <!-- Chi tiết đơn hàng -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-warning bg-opacity-10 rounded">
                                    <span class="text-warning fw-medium">🎫 Vé phim:</span>
                                    <span class="fw-bold text-warning">\${movieBooking}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-danger bg-opacity-10 rounded">
                                    <span class="text-danger fw-medium">🍔 Món ăn:</span>
                                    <span class="fw-bold text-danger">\${foodOrder}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        },
        responsive: [{
            breakpoint: 768,
            options: {
                chart: {
                    height: 350
                },
                xaxis: {
                    labels: {
                        rotate: -45,
                        style: {
                            fontSize: '10px'
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
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
        return "updateDatarevenueChart";
    }

    public function compileJavascript()
    {
        $ctxText = "ctxrevenueChart";
        $optionsText = "optionsrevenueChart";
        $chartText = "chartrevenueChart";
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
