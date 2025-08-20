<?php

namespace App\Charts\ChartRooms;

use App\Models\Room;
class RoomStatsData {
    protected $data;

    protected function queryData(?string $filter = null){
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();
        $query = Room::select('rooms.id', 'rooms.name')
            ->leftJoin('showtimes', 'rooms.id', '=', 'showtimes.room_id')
            ->leftJoin('bookings', function ($join) {
                $join->on('showtimes.id', '=', 'bookings.showtime_id')
                    ->where('bookings.status', '=', 'paid');
            })
            ->leftJoin('booking_seats', 'bookings.id', '=', 'booking_seats.booking_id');
            $roomStats = $query->whereBetween('bookings.created_at',[$startDate, $endDate])
            ->groupBy('rooms.id', 'rooms.name')
            ->selectRaw('COUNT(booking_seats.id) as tickets_sold, COALESCE(SUM(bookings.total_price), 0) as revenue')
            ->get();

        if ($roomStats->isEmpty() || $roomStats->every(fn($room) => $room->tickets_sold == 0)) {

            $allRooms = Room::select('id', 'name')->get();
            $labels = $allRooms->pluck('name')->toArray();

            if (empty($labels)) {
                $labels = ['Không có dữ liệu'];
            }

            $ticketsData = array_fill(0, count($labels), 0);
            $revenueData = array_fill(0, count($labels), 0);
        } else {
            $labels = $roomStats->pluck('name')->toArray();
            $ticketsData = $roomStats->pluck('tickets_sold')->map(fn($val) => (int)$val)->toArray();
            $revenueData = $roomStats->pluck('revenue')->map(fn($val) => (int)$val)->toArray();
        }

        return [
            'labels' => $labels,
            'tickets' => $ticketsData,
            'revenue' => $revenueData
        ];
    }

    public function loadData(?string $filter = null){
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement(){
        return "document.getElementById('allRoomsStatsChart')";
    }

    protected function buildChartConfig(){
        $roomLabels=$this->data['labels'];
        $roomLabelsJS = json_encode($roomLabels);

        $roomTickets=$this->data['tickets'];
        $roomTicketsJS = json_encode($roomTickets);

            $roomRevenue=$this->data['revenue'];
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
                            fontSize: '12px'
                        }
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
        return "updateDataChartRoom";
}

    public function compileJavascript(){
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
