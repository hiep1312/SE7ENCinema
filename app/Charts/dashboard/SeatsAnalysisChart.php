<?php

namespace App\Charts\dashboard;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SeatsAnalysisChart
{
    protected $data;

    protected function queryData(?array $filter = null)
    {
        is_array($filter) && [$fromDate, $rangeDays] = $filter;
        $rangeDays = (int) $rangeDays;
        $fromDate = $fromDate ? Carbon::parse($fromDate) : Carbon::now()->subDays($rangeDays);
        $toDate = $fromDate->copy()->addDays($rangeDays);

        $startDate = $fromDate->copy()->startOfDay();
        $endDate = $toDate->copy()->endOfDay();

        // Tổng sức chứa theo ngày (cộng capacity của các phòng có suất chiếu trong ngày)
        $capacityByDay = DB::table('showtimes')
            ->join('rooms', 'showtimes.room_id', '=', 'rooms.id')
            ->whereBetween('showtimes.start_time', [$startDate, $endDate])
            ->select(DB::raw('DATE(showtimes.start_time) as day'), DB::raw('SUM(rooms.capacity) as total_capacity'))
            ->groupBy('day')
            ->pluck('total_capacity', 'day');

        // Số ghế đã bán theo loại ghế và ngày
        $soldByTypeAndDay = DB::table('booking_seats')
            ->join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('seats', 'booking_seats.seat_id', '=', 'seats.id')
            ->where('bookings.status', 'paid')
            ->whereBetween('showtimes.start_time', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(showtimes.start_time) as day'),
                'seats.seat_type as seat_type',
                DB::raw('COUNT(*) as sold_count')
            )
            ->groupBy('day', 'seat_type')
            ->get();

        // Chuẩn bị map ngày -> các số liệu
        $dayToStats = [];

        // Khởi tạo 7 ngày gần nhất
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i)->toDateString();
            $dayToStats[$day] = [
                'standard' => 0,
                'vip' => 0,
                'couple' => 0,
                'empty' => 0,
                'capacity' => (int) ($capacityByDay[$day] ?? 0),
            ];
        }
        // Gán số ghế bán theo loại
        foreach ($soldByTypeAndDay as $row) {
            $day = $row->day;
            if (!isset($dayToStats[$day])) continue;
            $type = $row->seat_type;
            $count = (int) $row->sold_count;
            if (isset($dayToStats[$day][$type])) {
                $dayToStats[$day][$type] += $count;
            }
        }

        // Tính ghế trống và % lấp đầy
        $result = [];
        foreach ($dayToStats as $day => $stats) {
            $soldTotal = $stats['standard'] + $stats['vip'] + $stats['couple'];
            $capacity = max(0, (int) $stats['capacity']);
            $empty = max(0, $capacity - $soldTotal);
            $occupancy = $capacity > 0 ? round(($soldTotal / $capacity) * 100) : 0;

            $result[] = [
                'day' => $day,
                'label' => Carbon::parse($day)->locale('vi')->dayName, // tên ngày trong tuần
                'standard' => (int) $stats['standard'],
                'vip' => (int) $stats['vip'],
                'couple' => (int) $stats['couple'],
                'empty' => (int) $empty,
                'capacity' => (int) $capacity,
                'sold' => (int) $soldTotal,
                'occupancy' => (int) $occupancy,
            ];
        }
        return collect($result);
    }

    public function loadData(?array $filter = null)
    {
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement()
    {
        return "document.getElementById('seatsAnalysisChart')";
    }

    protected function buildChartConfig()
    {
        $rows = $this->data;
        $labels = $rows->map(fn($r) => $r['label'])->toJson();
        $standard = $rows->map(fn($r) => $r['standard'])->toJson();
        $vip = $rows->map(fn($r) => $r['vip'])->toJson();
        $couple = $rows->map(fn($r) => $r['couple'])->toJson();
        $empty = $rows->map(fn($r) => $r['empty'])->toJson();
        $occupancy = $rows->map(fn($r) => $r['occupancy'])->toJson();
        $soldTotals = $rows->map(fn($r) => $r['sold'])->toJson();
        $capacityTotals = $rows->map(fn($r) => $r['capacity'])->toJson();

        return <<<JS
        {
            chart: {
                height: 320,
                type: 'bar',
                stacked: true,
                background: 'transparent',
                toolbar: { show: true }
            },
            colors: ['#3B82F6', '#F59E0B', '#EF4444', '#6B7280'],
            series: [
                { name: 'Ghế Thường', data: $standard },
                { name: 'Ghế VIP', data: $vip },
                { name: 'Ghế Couple', data: $couple },
                { name: 'Ghế trống', data: $empty }
            ],
            xaxis: {
                categories: $labels,
                labels: { style: { colors: '#ffffff', fontSize: '12px' } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                title: { text: 'Số lượng ghế', style: { color: '#9CA3AF' } },
                labels: {
                    style: { colors: '#ffffff', fontSize: '12px' },
                    formatter: function(value) { return new Intl.NumberFormat('vi-VN').format(value); }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 6,
                    dataLabels: {
                        total: {
                            enabled: true,
                            style: { color: '#FFFFFF', fontWeight: 700 },
                            formatter: function(w) {
                                const occ = $occupancy;
                                const i = w.dataPointIndex;
                                return (occ[i] || 0) + '%';
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                labels: { colors: '#ffffff' }
            },
            tooltip: {
                theme: 'dark',
                custom: function({ dataPointIndex, w }) {
                    const labels = $labels;
                    const std = $standard; const v = $vip; const c = $couple; const e = $empty;
                    const occ = $occupancy; const sold = $soldTotals; const cap = $capacityTotals;
                    const day = labels[dataPointIndex] || '';
                    const totalSeats = (cap[dataPointIndex] || 0);
                    const soldSeats = (sold[dataPointIndex] || 0);
                    const stdVal = (std[dataPointIndex] || 0);
                    const vipVal = (v[dataPointIndex] || 0);
                    const coupleVal = (c[dataPointIndex] || 0);
                    const emptyVal = (e[dataPointIndex] || 0);
                    const occVal = (occ[dataPointIndex] || 0);

                    return `
                    <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 280px;">
                        <div class="d-flex align-items-center mb-2">
                            <span class="me-2">📅</span>
                            <h6 class="mb-0 text-white fw-bold">\${day}</h6>
                        </div>
                        <div class="d-flex justify-content-between"><span class="text-muted">Tổng ghế:</span><span class="text-white fw-bold">\${totalSeats.toLocaleString('vi-VN')}</span></div>
                        <div class="d-flex justify-content-between"><span class="text-primary">Ghế đã bán:</span><span class="text-primary fw-bold">\${soldSeats.toLocaleString('vi-VN')}</span></div>
                        <div class="mt-2">
                            <div class="d-flex justify-content-between"><span>• Thường</span><span class="fw-semibold">\${stdVal.toLocaleString('vi-VN')}</span></div>
                            <div class="d-flex justify-content-between"><span>• VIP</span><span class="fw-semibold">\${vipVal.toLocaleString('vi-VN')}</span></div>
                            <div class="d-flex justify-content-between"><span>• Couple</span><span class="fw-semibold">\${coupleVal.toLocaleString('vi-VN')}</span></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2"><span class="text-muted">Ghế trống:</span><span class="text-white fw-bold">\${emptyVal.toLocaleString('vi-VN')}</span></div>
                        <div class="d-flex justify-content-between mt-2"><span class="text-success">Tỷ lệ lấp đầy:</span><span class="text-success fw-bold">\${occVal}%</span></div>
                    </div>`;
                }
            },
            grid: { borderColor: '#2d3748' }
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
        return "updateDataSeatsAnalysisChart";
    }

    public function compileJavascript()
    {
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
