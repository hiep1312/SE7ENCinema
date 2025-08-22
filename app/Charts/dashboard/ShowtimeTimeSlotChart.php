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
                height: 350,
                type: 'bar',
                background: 'transparent',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '13px',
                    fontFamily: 'Inter, system-ui, sans-serif'
                },
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
                    <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 320px;">
                        <div class="d-flex align-items-center mb-3" style="border-bottom:1px solid rgba(75,85,99,0.3);padding-bottom:8px;">
                            <span class="me-2">🕐</span>
                            <h6 class="mb-0 text-white fw-bold">Khung giờ \${gio}</h6>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-primary">🎬 Suất chiếu:</span>
                            <span class="fw-bold text-primary">\${suatChieu.toLocaleString('vi-VN')}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-success">🛒 Đơn hàng:</span>
                            <span class="fw-bold text-success">\${donHang.toLocaleString('vi-VN')}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-warning">🎫 Vé bán:</span>
                            <span class="fw-bold text-warning">\${ve.toLocaleString('vi-VN')}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-danger">💰 Doanh thu:</span>
                            <span class="fw-bold text-danger">\${new Intl.NumberFormat('vi-VN').format(doanhThu)}đ</span>
                        </div>
                    </div>
                    `;
                }
            },
            dataLabels: { enabled: false },
            colors: ['#3B82F6', '#F59E0B'],
            series: [
                { name: 'Suất chiếu', data: $showtimes },
                { name: 'Vé bán', data: $tickets }
            ],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 6
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'vertical',
                    shadeIntensity: 0.3,
                    gradientToColors: ['#1E40AF', '#D97706'],
                    inverseColors: false,
                    opacityFrom: 0.8,
                    opacityTo: 0.3,
                    stops: [0, 100]
                }
            },
            stroke: {
                width: 1,
                colors: ['#111827']
            },
            xaxis: {
                categories: $labels,
                labels: {
                    style: {
                        colors: '#ffffff',
                        fontSize: '12px',
                        fontWeight: 500
                    },
                    rotate: 0,
                    maxHeight: 60
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                title: {
                    text: 'Số lượng',
                    style: { color: '#9CA3AF', fontSize: '14px', fontWeight: 600 }
                },
                labels: {
                    style: { colors: '#ffffff', fontSize: '12px' },
                    formatter: function(value) { return new Intl.NumberFormat('vi-VN').format(value); }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center',
                labels: { colors: '#ffffff', fontSize: '13px', fontWeight: 600 },
                markers: { width: 12, height: 12, radius: 6 },
                itemMargin: { horizontal: 20, vertical: 8 }
            },
            grid: {
                borderColor: '#374151',
                strokeDashArray: 5,
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: true } }
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
