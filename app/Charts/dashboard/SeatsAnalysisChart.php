<?php

namespace App\Charts\dashboard;

use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Support\Facades\DB;

class SeatsAnalysisChart {
    protected $data;

    protected function queryData(?string $filter = null){
        $startDate = now()->subDays(30)->startOfDay();
        $endDate = now()->endOfDay();
        
        $query = Booking::select([
            DB::raw('DATE(bookings.created_at) as date'),
            DB::raw('COUNT(DISTINCT bookings.id) as total_bookings'),
            DB::raw('SUM(booking_seats_count) as total_seats'),
            DB::raw('AVG(booking_seats_count) as avg_seats_per_booking'),
            DB::raw('COUNT(DISTINCT showtimes.movie_id) as total_movies'),
            DB::raw('COUNT(DISTINCT showtimes.room_id) as total_rooms')
        ])
            ->join(DB::raw('(SELECT booking_id, COUNT(*) as booking_seats_count FROM booking_seats GROUP BY booking_id) as seat_counts'), 'bookings.id', '=', 'seat_counts.booking_id')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->where('bookings.status', 'paid')
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(bookings.created_at)'))
            ->orderBy('date')
            ->limit(30)
            ->get();

        return $query;
    }

    public function loadData(?string $filter = null){
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement(){
        return "document.getElementById('seatsAnalysisChart')";
    }

    protected function buildChartConfig(){
        $seatsData = $this->data;
        $labels = $seatsData->map(fn($item) => $item->date)->toJson();
        $totalSeats = $seatsData->map(fn($item) => $item->total_seats)->toJson();
        $totalBookings = $seatsData->map(fn($item) => $item->total_bookings)->toJson();

        return <<<JS
        {
            chart: {
                height: 300,
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
                            filename: 'phan-tich-ghe',
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
                            filename: 'phan-tich-ghe',
                        },
                        png: {
                            filename: 'phan-tich-ghe',
                        }
                    },
                },
                zoom: {
                    enabled: false,
                    type: 'x',
                    autoScaleYaxis: true,
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
                    const seatsData = $totalSeats;
                    const bookingsData = $totalBookings;

                    const ngay = dates[dataPointIndex] || '';
                    const tongGhe = seatsData[dataPointIndex] || 0;
                    const tongDon = bookingsData[dataPointIndex] || 0;

                    return `
                    <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 280px;">
                        <div class="d-flex align-items-center mb-3">
                            <span class="fs-5 me-2">📅</span>
                            <h6 class="mb-0 text-white fw-bold">\${ngay}</h6>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-success">💺 Tổng ghế:</span>
                            <span class="fw-bold fs-6 text-success">\${tongGhe}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-info">🛒 Tổng đơn hàng:</span>
                            <span class="fw-bold fs-6 text-info">\${tongDon}</span>
                        </div>
                    </div>
                    `;
                }
            },
            dataLabels: {
                enabled: false
            },
            colors: ['#28a745', '#17a2b8'],
            series: [{
                    name: 'Tổng ghế',
                    type: 'bar',
                    data: $totalSeats
                },
                {
                    name: 'Tổng đơn hàng',
                    type: 'line',
                    data: $totalBookings
                }
            ],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    endingShape: 'rounded',
                    borderRadius: 6,
                    distributed: false
                }
            },
            stroke: {
                width: [0, 3],
                curve: 'smooth'
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
            },
            yaxis: {
                title: {
                    text: 'Số lượng',
                    style: {
                        color: '#28a745'
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
            },
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
        return "updateDataSeatsAnalysisChart";
    }

    public function compileJavascript(){
        $ctxText = "ctxSeatsAnalysisChart";
        $optionsText = "optionsSeatsAnalysisChart";
        $chartText = "chartSeatsAnalysisChart";
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
