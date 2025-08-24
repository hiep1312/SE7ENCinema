<?php

namespace App\Charts\dashboard;

use App\Models\Booking;
use App\Models\Showtime;
use Illuminate\Support\Facades\DB;

class ShowtimeTimeSlotChart {
    protected $data;

    protected function queryData(?string $filter = null){
        $startDate = now()->subDays(30)->startOfDay();
        $endDate = now()->endOfDay();
        
        $query = Showtime::select([
            DB::raw('HOUR(start_time) as hour'),
            DB::raw('COUNT(DISTINCT showtimes.id) as total_showtimes'),
            DB::raw('COUNT(DISTINCT bookings.id) as total_bookings'),
            DB::raw('SUM(booking_seats_count) as total_tickets'),
            DB::raw('SUM(bookings.total_price) as total_revenue')
        ])
            ->leftJoin('bookings', 'showtimes.id', '=', 'bookings.showtime_id')
            ->leftJoin(DB::raw('(SELECT booking_id, COUNT(*) as booking_seats_count FROM booking_seats GROUP BY booking_id) as seat_counts'), 'bookings.id', '=', 'seat_counts.booking_id')
            ->whereBetween('showtimes.start_time', [$startDate, $endDate])
            ->groupBy(DB::raw('HOUR(start_time)'))
            ->orderBy('hour')
            ->get();

        return $query;
    }

    public function loadData(?string $filter = null){
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement(){
        return "document.getElementById('showtimeTimeSlotChart')";
    }

    protected function buildChartConfig(){
        $timeData = $this->data;
        $labels = $timeData->map(fn($item) => $item->hour . 'h')->toJson();
        $showtimes = $timeData->map(fn($item) => $item->total_showtimes)->toJson();
        $bookings = $timeData->map(fn($item) => $item->total_bookings)->toJson();
        $tickets = $timeData->map(fn($item) => $item->total_tickets)->toJson();
        $revenue = $timeData->map(fn($item) => $item->total_revenue)->toJson();

        return <<<JS
        {
            chart: {
                height: 300,
                type: 'line',
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
                            filename: 'khung-gio-suat-chieu',
                            columnDelimiter: ',',
                            headerCategory: 'Giờ',
                            headerValue: 'Số lượng',
                            categoryFormatter: function(x) {
                                return x;
                            },
                            valueFormatter: function(y) {
                                return new Intl.NumberFormat('vi-VN').format(y);
                            }
                        },
                        svg: {
                            filename: 'khung-gio-suat-chieu',
                        },
                        png: {
                            filename: 'khung-gio-suat-chieu',
                        }
                    },
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
                    const hours = $labels;
                    const showtimesData = $showtimes;
                    const bookingsData = $bookings;
                    const ticketsData = $tickets;
                    const revenueData = $revenue;

                    const gio = hours[dataPointIndex] || '';
                    const suatChieu = showtimesData[dataPointIndex] || 0;
                    const donHang = bookingsData[dataPointIndex] || 0;
                    const ve = ticketsData[dataPointIndex] || 0;
                    const doanhThu = revenueData[dataPointIndex] || 0;

                    return `
                    <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 300px;">
                        <div class="d-flex align-items-center mb-3">
                            <span class="fs-5 me-2">🕐</span>
                            <h6 class="mb-0 text-white fw-bold">Khung giờ \${gio}</h6>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-primary">🎬 Suất chiếu:</span>
                            <span class="fw-bold fs-6 text-primary">\${suatChieu}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-success">🛒 Đơn hàng:</span>
                            <span class="fw-bold fs-6 text-success">\${donHang}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-warning">🎫 Vé bán:</span>
                            <span class="fw-bold fs-6 text-warning">\${ve}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-danger">💰 Doanh thu:</span>
                            <span class="fw-bold fs-6 text-danger">\${new Intl.NumberFormat('vi-VN').format(doanhThu)}đ</span>
                        </div>
                    </div>
                    `;
                }
            },
            dataLabels: {
                enabled: false
            },
            colors: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
            series: [{
                    name: 'Suất chiếu',
                    type: 'column',
                    data: $showtimes
                },
                {
                    name: 'Đơn hàng',
                    type: 'line',
                    data: $bookings
                },
                {
                    name: 'Vé bán',
                    type: 'area',
                    data: $tickets
                }
            ],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '70%',
                    endingShape: 'rounded',
                    borderRadius: 4
                }
            },
            fill: {
                type: ['solid', 'solid', 'gradient'],
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100],
                    colorStops: [
                        [],
                        [],
                        [{
                                offset: 0,
                                color: '#ffc107',
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
                width: [0, 3, 0],
                curve: 'smooth'
            },
            xaxis: {
                categories: $labels,
                labels: {
                    style: {
                        colors: '#ffffff',
                        fontSize: '12px'
                    },
                    rotate: 0,
                    maxHeight: 60
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
            },
            yaxis: [{
                title: {
                    text: 'Số lượng',
                    style: {
                        color: '#007bff'
                    }
                },
                labels: {
                    style: {
                        colors: '#ffffff',
                        fontSize: '12px'
                    },
                    formatter: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value);
                    }
                }
            }, {
                opposite: true,
                title: {
                    text: 'Doanh thu (VND)',
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
            }],
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
        return "updateDataShowtimeTimeSlotChart";
    }

    public function compileJavascript(){
        $ctxText = "ctxShowtimeTimeSlotChart";
        $optionsText = "optionsShowtimeTimeSlotChart";
        $chartText = "chartShowtimeTimeSlotChart";
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
