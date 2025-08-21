<?php

namespace App\Charts\admin\movie;

use App\Models\Booking;
use Carbon\Carbon;

class ratioChart
{
    protected $data;
    protected $movie;

    public function __construct($movie)
    {
        $this->movie = $movie;
    }

    protected function queryData(?array $filter = null)
    {
        is_array($filter) && [$fromDate, $rangeDays] = $filter;
        $rangeDays = (int) $rangeDays;

        $fromCheckinChart = Carbon::parse($fromDate)->startOfDay();
        $toCheckinChart   = ($fromCheckinChart && $rangeDays) ? (clone $fromCheckinChart)->addDays($rangeDays)->endOfDay() : null;
        $bookings = Booking::whereHas('showtime', function ($q) {
            $q->where('movie_id', $this->movie->id);
        })->with(['showtime.room', 'foodOrderItems', 'user']);

        $bookingChart = Booking::whereHas('showtime', function ($q) {
            $q->where('movie_id', $this->movie->id);
        })->with(['showtime.room'])->get();

        $paidBookings = (clone $bookings)
            ->where('status', 'paid')
            ->when($toCheckinChart, function ($query) use ($fromCheckinChart, $toCheckinChart) {
                $query->whereBetween('created_at', [$fromCheckinChart, $toCheckinChart]);
            }, function ($query) use ($fromCheckinChart) {
                $query->where('created_at', '>=', $fromCheckinChart);
            })
            ->with('seats')
            ->get();

        $seatCounts = $paidBookings
            ->flatMap->seats
            ->groupBy('seat_type')
            ->map->count();

        $showtimes = (clone $bookingChart)
            ->pluck('showtime')
            ->unique('id')
            ->when($toCheckinChart, function ($sts) use ($fromCheckinChart, $toCheckinChart) {
                return $sts->whereBetween('start_time', [$fromCheckinChart, $toCheckinChart]);
            }, function ($sts) use ($fromCheckinChart) {
                return $sts->where('start_time', '>=', $fromCheckinChart);
            });

        $totalCapacity = $showtimes->groupBy('room_id')->sum(function ($sts) {
            $room = $sts->first()->room;
            return $room->seats->count() * $sts->count();
        });

        // tổng ghế
        $caps = $totalCapacity;
        // tổng ghế đã đặt
        $totalBooked = $seatCounts->sum();
        // số ghế còn lại
        $remainingSeats = $caps - $totalBooked;

        $seatCountsWithRemaining = $seatCounts->toArray();
        $seatCountsWithRemaining['remaining'] = $remainingSeats;

        return [
            'seatCounts' => $seatCountsWithRemaining
        ];
    }


    public function loadData(?array $filter = null)
    {
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement()
    {
        return "document.getElementById('checkinChart')";
    }

    protected function buildChartConfig()
    {
            $vipSeats = $this->data['seatCounts']['vip'] ?? 0;
            $standardSeats = $this->data['seatCounts']['standard'] ?? 0;
            $disabledSeats = $this->data['seatCounts']['disabled'] ?? 0;
            $coupleSeats = $this->data['seatCounts']['couple'] ?? 0;
            $remainingSeats = $this->data['seatCounts']['remaining'] ?? 0;

            $vipSeatsJS = json_encode($vipSeats);
            $standardSeatsJS = json_encode($standardSeats);
            $disabledSeatsJS = json_encode($disabledSeats);
            $coupleSeatsJS = json_encode($coupleSeats);
            $remainingSeatsJS = json_encode($remainingSeats);
        /* Viết cấu hình biểu đồ tại đây */
        return <<<JS
        {
            series: [$remainingSeatsJS,$vipSeatsJS,$standardSeatsJS,$disabledSeatsJS,$coupleSeatsJS],
            labels: ['Số vé còn lại','Vé VIP','Vé thường','Disabled','Vé đôi'],
            chart: {
                type: 'donut',
                height: 400,
                background: 'transparent',
                toolbar: { show: true },
                animations: {
                    enabled: false,
                },
            },
            colors: ['#34A853', '#FBBC04', '#898371ff', '#000000ff', '#d0069aff'],
            stroke: { show: false },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '14px',
                    fontWeight: 600,
                    colors: ['#fff']
                },
                formatter: function (val, opts) {
                    return Math.round(val) + '%';
                }
            },
            plotOptions: {
                pie: {
                    expandOnClick: false,
                    donut: { 
                        size: '65%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                            },
                            value:{
                                show: true,
                                color:['#fff']
                            },
                            total:{
                                show: true,
                                color:['#fff']
                            }
                        } 
                    }
                }
            },
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                offsetY: 10,
                labels: { colors: '#f8f9fa' }, /* Light text color */
                markers: {
                    width: 12,
                    height: 12,
                    fillColors: ['#34A853', '#FBBC04', '#898371ff', '#000000ff', '#d0069aff'],
                    radius: 3
                }
            }
        }
        JS;
    }

    public function getFilterText(string $filterValue)
    {
        return match ($filterValue) {
            '3_days' => '3 ngày gần nhất',
            '7_days' => '7 ngày gần nhất',
            '15_days' => '15 ngày gần nhất',
            '30_days' => '30 ngày gần nhất',
            '3_months' => '3 tháng gần nhất',
            '6_months' => '6 tháng gần nhất',
            '9_months' => '9 tháng gần nhất',
            '1_year' => '1 năm gần nhất',
            '2_years' => '2 năm gần nhất',
            '3_years' => '3 năm gần nhất',
            '6_years' => '6 năm gần nhất',
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
        return "updateDataratioChart";
    }

    public function compileJavascript()
    {
        $ctxText = "ctxratioChart";
        $optionsText = "optionsratioChart";
        $chartText = "chartratioChart";
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
