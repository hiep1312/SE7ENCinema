<?php

namespace App\Charts\booking;

use App\Livewire\Booking\BookingFood;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class MoviesSummary {
    protected $data;

    protected function queryData(?string $filter = null){
        /* Viết truy vấn CSDL tại đây */
        $query = Booking::select([
            'movies.title as movie_title',
            DB::raw('SUM(bookings.total_price) as total_revenue'),
            DB::raw('COUNT(*) as total_bookings'),
            DB::raw('SUM(booking_seats_count) as total_tickets')
        ])
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->where('bookings.status', 'paid')
            ->join(DB::raw('(SELECT booking_id, COUNT(*) as booking_seats_count FROM booking_seats GROUP BY booking_id) as seat_counts'), 'bookings.id', '=', 'seat_counts.booking_id');

        return $query->groupBy('movies.title')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();
    }

    public function loadData(?string $filter = null){
        $this->data = $this->queryData();
    }

    protected function bindDataToElement(){
        return "document.getElementById('moviesSummaryChart')";
    }

    protected function buildChartConfig(){
        /* Viết cấu hình biểu đồ tại đây */
        $topMoviesData = $this->data;
        $labels = $topMoviesData->map(fn($item) => $item->movie_title)->toJson();
        $revenueData = $topMoviesData->map(fn($item) => $item->total_revenue)->toJson();
        $ticketsData = $topMoviesData->map(fn($item) => $item->total_tickets)->toJson();
        $bookingsData = $topMoviesData->map(fn($item) => $item->total_bookings)->toJson();

        return <<<JS
        {
            chart: {
                    height: 400,
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
                                filename: 'top-phim',
                                columnDelimiter: ',',
                                headerCategory: 'Phim',
                                headerValue: 'Doanh thu',
                                categoryFormatter: function(x) {
                                    return x;
                                },
                                valueFormatter: function(y) {
                                    return new Intl.NumberFormat('vi-VN').format(y);
                                }
                            },
                            svg: {
                                filename: 'top-phim',
                            },
                            png: {
                                filename: 'top-phim',
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
                        const movies = $labels;
                        const revenueData = $revenueData;
                        const ticketsData = $ticketsData;
                        const bookingsData = $bookingsData;

                        const phim = movies[dataPointIndex] || '';
                        const doanhThu = revenueData[dataPointIndex] || 0;
                        const veBan = ticketsData[dataPointIndex] || 0;
                        const donHang = bookingsData[dataPointIndex] || 0;

                        return `
                        <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 320px;">
                            <div class="d-flex align-items-center mb-3">
                                <span class="fs-5 me-2">🎬</span>
                                <h6 class="mb-0 text-white fw-bold">\${phim}</h6>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-warning">💰 Doanh thu:</span>
                                <span class="fw-bold fs-6 text-warning">\${new Intl.NumberFormat('vi-VN').format(doanhThu)}đ</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-info">🎫 Vé bán:</span>
                                <span class="fw-bold fs-6 text-info">\${veBan}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-white">🛒 Đơn hàng:</span>
                                <span class="fw-bold fs-6 text-white">\${donHang}</span>
                            </div>
                        </div>
                    `;
                    }
                },
                dataLabels: {
                    enabled: false,
                },
                series: [{
                        name: 'Doanh thu',
                        type: 'column',
                        data: $revenueData
                    },
                    {
                        name: 'Vé bán',
                        type: 'area',
                        data: $ticketsData
                    }
                ],
                fill: {
                    type: ['solid', 'gradient'],
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100],
                        colorStops: [
                            [],
                            [{
                                    offset: 0,
                                    color: '#4bc3e6',
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
                xaxis: {
                    categories: $labels,
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '12px'
                        },
                        rotate: -45,
                        rotateAlways: false
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
                            text: 'Doanh thu (VND)',
                            style: {
                                color: '#ffd700'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#ffd700',
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
                            text: 'Vé bán',
                            style: {
                                color: '#4bc3e6'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#4bc3e6',
                                fontSize: '12px'
                            },
                            formatter: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value);
                            }
                        }
                    }
                ],
                colors: ['#ffd700', '#4bc3e6'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '60%',
                        endingShape: 'rounded',
                        borderRadius: 4
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
        return "updateDatamoviesSummary";
    }

    public function compileJavascript(){
        $ctxText = "ctxmoviesSummary";
        $optionsText = "optionsmoviesSummary";
        $chartText = "chartmoviesSummary";
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
