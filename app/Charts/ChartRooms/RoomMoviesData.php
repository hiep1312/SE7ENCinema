<?php

namespace App\Charts\ChartRooms;

use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RoomMoviesData
{
    protected $data;
    protected $room;

    public function __construct($room)
    {
        $this->room = $room;
    }

    protected function queryData(?array $filter = null)
    {
        is_array($filter) && [$fromDate, $rangeDays] = $filter;
        $rangeDays = (int) $rangeDays;
        $fromDate = $fromDate ? Carbon::parse($fromDate) : Carbon::now()->subDays($rangeDays);
        $toDate = $fromDate->copy()->addDays($rangeDays);

        $startDate = $fromDate->copy()->startOfDay();
        $endDate = $toDate->copy()->endOfDay();

        $data = Seat::select(
            'seats.seat_type',
            DB::raw('COUNT(booking_seats.id) as tickets_sold'),
            DB::raw('COALESCE(SUM(booking_seats.ticket_price), 0) as revenue'),
            DB::raw('COUNT(seats.id) as total_seats')
        )
            ->leftJoin('booking_seats', 'seats.id', '=', 'booking_seats.seat_id')
            ->leftJoin('bookings', function ($join) use ($startDate, $endDate) {
                $join->on('booking_seats.booking_id', '=', 'bookings.id')
                    ->where('bookings.status', '=', 'paid')
                    ->whereBetween('bookings.created_at', [$startDate, $endDate]);
            })
            ->where('seats.room_id', $this->room->id)
            ->groupBy('seats.seat_type')
            ->orderBy('revenue', 'desc')
            ->get();
        if ($data->isEmpty()) {
            return [
                'labels' => ['Không có dữ liệu'],
                'tickets' => [0],
                'revenue' => [0],
                'total_seats' => [0]
            ];
        }

        $labels = [];
        $ticketsData = [];
        $revenueData = [];
        $totalSeatsData = [];

        foreach ($data as $item) {
            $seatTypeName = match ($item->seat_type) {
                'standard' => 'Ghế thường',
                'vip' => 'Ghế VIP',
                'couple' => 'Ghế đôi',
                default => 'Ghế ' . ucfirst($item->seat_type)
            };

            $labels[] = $seatTypeName;
            $ticketsData[] = max((int)$item->tickets_sold, 0);
            $revenueData[] = max((int)$item->revenue, 0);
            $totalSeatsData[] = max((int)$item->total_seats, 0);
        }

        return [
            'labels' => $labels,
            'tickets' => $ticketsData,
            'revenue' => $revenueData,
            'total_seats' => $totalSeatsData
        ];
    }

    public function loadData(?array $filter = null)
    {
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement()
    {
        return "document.getElementById('roomMoviesChart')";
    }

    protected function buildChartConfig()
    {
        $roomLabels = $this->data['labels'];
        $roomLabelsJS = json_encode($roomLabels);

        $roomTickets = $this->data['tickets'];
        $roomTicketsJS = json_encode($roomTickets);

        $roomRevenue = $this->data['revenue'];
        $roomRevenueJS = json_encode($roomRevenue);

        $roomTotalSeats = $this->data['total_seats'];
        $roomTotalSeatsJS = json_encode($roomTotalSeats);

        return <<<JS
        {
                chart: {
                    type: 'line',
                    height: 400,
                    background: 'transparent',
                    toolbar: {
                        show: true
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                series: [{
                    name: 'Doanh thu (VNĐ)',
                    type: 'column',
                    data: $roomRevenueJS,
                    yAxisIndex: 0
                }, {
                    name: 'Số vé đã bán',
                    type: 'line',
                    data: $roomTicketsJS,
                    yAxisIndex: 1
                }, {
                    name: 'Tổng ghế',
                    type: 'column',
                    data: $roomTotalSeatsJS,
                    yAxisIndex: 0,
                    opacity: 0.3
                }],
                xaxis: {
                    categories: $roomLabelsJS,
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: [{
                    title: {
                        text: 'Doanh thu (VNĐ)',
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '11px'
                        },
                        formatter: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value);
                        }
                    }
                }, {
                    opposite: true,
                    title: {
                        text: 'Số vé',
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '11px'
                        }
                    }
                }],
                colors: ['#17A2B8', '#20C997', '#6C757D'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'vertical',
                        shadeIntensity: 0.3,
                        gradientToColors: ['#138496', '#1EA085', '#495057'],
                        inverseColors: false,
                        opacityFrom: 0.9,
                        opacityTo: 0.6,
                        stops: [0, 100]
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '60%',
                        endingShape: 'rounded',
                        borderRadius: 6
                    }
                },
                stroke: {
                    width: [0, 4, 0],
                    curve: 'smooth'
                },
                grid: {
                    show: true,
                    borderColor: '#2d3748',
                    strokeDashArray: 1
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                tooltip: {
                    theme: 'dark',
                    y: [{
                        formatter: function(value) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            }).format(value);
                        }
                    }, {
                        formatter: function(value) {
                            return value + ' vé';
                        }
                    }, {
                        formatter: function(value) {
                            return value + ' ghế';
                        }
                    }]
                },

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
        return "updateDataRoomMoviesData";
    }

    public function compileJavascript()
    {
        $ctxText = "ctxRoomMoviesData";
        $optionsText = "optionsRoomMoviesData";
        $chartText = "chartRoomMoviesData";
        echo <<<JS
        Livewire.on("{$this->getEventName()}", async function ([data]){
            await new Promise(resolve => setTimeout(resolve));
            const {$ctxText} = {$this->bindDataToElement()};
            if({$ctxText}){
                try {
                    if(window.{$chartText} && typeof window.{$chartText}.destroy === 'function') {
                        window.{$chartText}.destroy();
                    }
                    window.{$optionsText} = data && typeof data === 'string' ? new Function("return " + data)() : {$this->buildChartConfig()};
                    window.{$chartText} = createScChart({$ctxText}, window.{$optionsText});
                } catch (e) { console.error(e); }
            }
        });
        JS;
    }
}
