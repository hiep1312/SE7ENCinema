<?php

namespace App\Charts\ChartRooms;

use App\Models\Showtime;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomStatsData
{
    protected $data;
    protected $room;

    public function __construct($room)
    {
        $this->room = $room;
    }

    protected function queryData(?string $filter = null)
    {
        $startDate = now()->subDays(2)->startOfDay();
        $endDate = now()->endOfDay();

        $data = Showtime::select(
            'showtimes.id',
            'showtimes.start_time',
            'movies.title as movie_title',
            DB::raw('COUNT(booking_seats.id) as tickets_sold'),
            DB::raw('COALESCE(SUM(bookings.total_price), 0) as revenue')
        )
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->leftJoin('bookings', function ($join) use ($startDate, $endDate) {
                $join->on('showtimes.id', '=', 'bookings.showtime_id')
                    ->where('bookings.status', '=', 'paid')
                    ->whereBetween('bookings.created_at', [$startDate, $endDate]);
            })
            ->leftJoin('booking_seats', 'bookings.id', '=', 'booking_seats.booking_id')
            ->where('showtimes.room_id', $this->room->id)
            ->whereBetween('showtimes.start_time', [$startDate, $endDate])
            ->where('showtimes.start_time', '<=', now())
            ->groupBy('showtimes.id', 'showtimes.start_time', 'movies.title')
            ->orderBy('showtimes.start_time', 'asc')
            ->get();

        $filtered = $data->filter(function ($item) {
            return $item->tickets_sold > 0 || $item->revenue > 0;
        });

        if ($filtered->isEmpty()) {
            return [
                'labels' => ['Không có dữ liệu'],
                'tickets' => [0],
                'revenue' => [0]
            ];
        }

        $labels = [];
        $ticketsData = [];
        $revenueData = [];

        foreach ($filtered as $item) {
            $labels[] = $item->movie_title . ' (' . $item->start_time->format('H:i') . ')';
            $ticketsData[] = (int) $item->tickets_sold;
            $revenueData[] = (int) $item->revenue;
        }

        return [
            'labels' => $labels,
            'tickets' => $ticketsData,
            'revenue' => $revenueData
        ];
    }


    public function loadData(?string $filter = null)
    {
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement()
    {
        return "document.getElementById('allRoomsStatsChart')";
    }

    protected function buildChartConfig()
    {
        $roomLabels = $this->data['labels'];
        $roomLabelsJS = json_encode($roomLabels);

        $roomTickets = $this->data['tickets'];
        $roomTicketsJS = json_encode($roomTickets);

        $roomRevenue = $this->data['revenue'];
        $roomRevenueJS = json_encode($roomRevenue);

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
                        name: 'Vé đã bán',
                        type: 'column',
                        data: $roomTicketsJS
                    },
                    {
                        name: 'Doanh thu (VNĐ)',
                        type: 'line',
                        yAxisIndex: 1,
                        data: $roomRevenueJS
                    }
                ],
                xaxis: {
                    categories: $roomLabelsJS,
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '11px'
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
                            text: 'Số vé',
                            style: {
                                color: '#ffffff'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#ffffff',
                                fontSize: '12px'
                            }
                        }
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Doanh thu (VNĐ)',
                            style: {
                                color: '#ffffff'
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
                    }
                ],
                colors: ['#4285F4', '#FBBC04'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'vertical',
                        shadeIntensity: 0.3,
                        gradientToColors: ['#1976D2', '#FF6B35'],
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
                        borderRadius: 8
                    }
                },
                stroke: {
                    width: [0, 4],
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
                                return value + ' vé';
                            }
                        },
                        {
                            formatter: function(value) {
                                return new Intl.NumberFormat('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND'
                                }).format(value);
                            }
                        }
                    ]
                }
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
        return "updateDataChartRoom";
    }

    public function compileJavascript()
    {
        $ctxText = "ctxChartRoom";
        $optionsText = "optionsChartRoom";
        $chartText = "chartRoom";
        echo <<<JS
        Livewire.on("{$this->getEventName()}", async function ([data]){
            await new Promise(resolve => setTimeout(resolve,100));
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
