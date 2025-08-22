<?php

namespace App\Charts\dashboard;

use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TopMoviesChart {
    protected $data;

    protected function queryData(?string $filter = null){
        $startDate = now()->subDays(7)->startOfDay();
        $endDate = now()->endOfDay();
        
        $query = Booking::select([
            DB::raw('DATE(bookings.created_at) as booking_date'),
            DB::raw('SUM(bookings.total_price) as total_revenue'),
            DB::raw('COUNT(*) as total_bookings'),
            DB::raw('SUM(booking_seats_count) as total_tickets')
        ])
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->where('bookings.status', 'paid')
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->join(DB::raw('(SELECT booking_id, COUNT(*) as booking_seats_count FROM booking_seats GROUP BY booking_id) as seat_counts'), 'bookings.id', '=', 'seat_counts.booking_id');

        return $query->groupBy(DB::raw('DATE(bookings.created_at)'))
            ->orderBy('booking_date')
            ->get();
    }

    public function loadData(?string $filter = null){
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement(){
        return "document.getElementById('topMoviesChart')";
    }

    protected function buildChartConfig(){
        $topMoviesData = $this->data;
        $labels = $topMoviesData->map(fn($item) => \Carbon\Carbon::parse($item->booking_date)->format('d/m'))->toJson();
        $revenueData = $topMoviesData->map(fn($item) => $item->total_revenue)->toJson();
        $ticketsData = $topMoviesData->map(fn($item) => $item->total_tickets)->toJson();
        $bookingsData = $topMoviesData->map(fn($item) => $item->total_bookings)->toJson();
        
        // Lấy ngày đầy đủ cho tooltip
        $fullDates = $topMoviesData->map(fn($item) => \Carbon\Carbon::parse($item->booking_date)->format('d/m/Y'))->toJson();

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
                            filename: 'top-phim',
                            columnDelimiter: ',',
                            headerCategory: 'Ngày',
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
                    const dates = $labels;
                    const revenueData = $revenueData;
                    const ticketsData = $ticketsData;
                    const bookingsData = $bookingsData;
                    const fullDates = $fullDates;

                    const ngay = fullDates[dataPointIndex] || '';
                    const doanhThu = revenueData[dataPointIndex] || 0;
                    const veBan = ticketsData[dataPointIndex] || 0;
                    const donHang = bookingsData[dataPointIndex] || 0;

                    return `
                    <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 320px;">
                        <div class="d-flex align-items-center mb-3">
                            <span class="fs-5 me-2">📅</span>
                            <h6 class="mb-0 text-white fw-bold">Ngày \${ngay}</h6>
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
                    data: $revenueData
                }
            ],
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
                }
            },
            yaxis: {
                title: {
                    text: 'Doanh thu (VND)',
                    style: {
                        color: '#9CA3AF',
                        fontSize: '14px',
                        fontWeight: 600
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
            colors: ['#ffd700'],
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
        return "updateDataTopMoviesChart";
    }

    public function compileJavascript(){
        $ctxText = "ctxTopMoviesChart";
        $optionsText = "optionsTopMoviesChart";
        $chartText = "chartTopMoviesChart";
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
