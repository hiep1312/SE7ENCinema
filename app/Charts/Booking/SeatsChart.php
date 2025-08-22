<?php

namespace App\Charts\booking;

use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Support\Facades\DB;

class SeatsChart {
    protected $data;

    protected function queryData(){
        /* Viết truy vấn CSDL tại đây */
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
            ->groupBy(DB::raw('DATE(bookings.created_at)'))
            ->orderBy('date')
            ->limit(30)
            ->get();

        return $query;
    }

    public function loadData(){
        $this->data = $this->queryData();
    }

    protected function bindDataToElement(){
        return "document.getElementById('seatsChart')";
    }

    protected function buildChartConfig(){
        /* Viết cấu hình biểu đồ tại đây */
        $seatsData = $this->data;
        $labels = $seatsData->map(fn($item) => $item->date)->toJson();
        $totalSeats = $seatsData->map(fn($item) => $item->total_seats)->toJson();
        $totalBookings = $seatsData->map(fn($item) => $item->total_bookings)->toJson();
        $totalMovies = $seatsData->map(fn($item) => $item->total_movies)->toJson();
        $totalRooms = $seatsData->map(fn($item) => $item->total_rooms)->toJson();

        return <<<JS
        {
            chart: {
                    height: 500,
                    type: 'area',
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
                        const moviesData = $totalMovies;
                        const roomsData = $totalRooms;

                        const ngay = dates[dataPointIndex] || '';
                        const tongGhe = seatsData[dataPointIndex] || 0;
                        const tongDon = bookingsData[dataPointIndex] || 0;
                        const soPhim = moviesData[dataPointIndex] || 0;
                        const soPhong = roomsData[dataPointIndex] || 0;

                        return `
                        <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 320px;">
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
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-warning">🎬 Số phim:</span>
                                <span class="fw-bold fs-6 text-warning">\${soPhim}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-danger">🏢 Số phòng:</span>
                                <span class="fw-bold fs-6 text-danger">\${soPhong}</span>
                            </div>
                        </div>
                    `;
                    }
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['#28a745', '#17a2b8', '#ffc107', '#dc3545'],
                series: [{
                        name: 'Tổng ghế',
                        type: 'area',
                        data: $totalSeats
                    },
                    {
                        name: 'Tổng đơn hàng',
                        type: 'line',
                        data: $totalBookings
                    },
                    {
                        name: 'Số phim',
                        type: 'column',
                        data: $totalMovies
                    },
                    {
                        name: 'Số phòng',
                        type: 'column',
                        data: $totalRooms
                    }
                ],
                fill: {
                    type: ['gradient', 'solid', 'solid'],
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100],
                        colorStops: [
                            [{
                                    offset: 0,
                                    color: '#28a745',
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
                    width: [0, 4, 2],
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
                yaxis: [{
                        title: {
                            text: 'Số lượng',
                            style: {
                                color: '#28a745'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#28a745',
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
                            text: 'Số phim/phòng',
                            style: {
                                color: '#ffc107'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#ffc107',
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
        return "updateDataSeatsChart";
    }

    public function compileJavascript(){
        $ctxText = "ctxSeatsChart";
        $optionsText = "optionsSeatsChart";
        $chartText = "chartSeatsChart";
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
