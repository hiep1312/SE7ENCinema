<?php

namespace App\Charts\ChartRooms;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class RoomMoviesData {
    protected $data;
    protected $room;

    public function __construct($room) {
        $this->room = $room;
    }

    protected function queryData(?string $filter = null){
        $startDate = now()->subDays(2)->startOfDay();
        $endDate = now()->endOfDay();

        $data = Booking::select('movies.title', DB::raw('COUNT(booking_seats.id) as tickets_sold'), DB::raw('SUM(bookings.total_price) as revenue'))
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->join('booking_seats', 'bookings.id', '=', 'booking_seats.booking_id')
            ->where('showtimes.room_id', $this->room->id)
            ->where('bookings.status', 'paid')
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('movies.id', 'movies.title')
            ->orderByDesc('tickets_sold')
            ->limit(10)
            ->get();

        if ($data->isEmpty()) {
            return [
                'labels' => ['Không có dữ liệu'],
                'tickets' => [0],
                'revenue' => [0]
            ];
        }

        $labels = [];
        $ticketsData = [];
        $revenueData = [];

        foreach ($data as $item) {
            $labels[] = $item->title ?? 'Không có tên';
            $ticketsData[] = max((int)$item->tickets_sold, 0);
            $revenueData[] = max((int)$item->revenue, 0);
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
        return "document.getElementById('roomMoviesChart')";
    }

    protected function buildChartConfig(){
        $roomLabels=$this->data['labels'];
        $roomLabelsJS = json_encode($roomLabels);

        $roomTickets=$this->data['tickets'];
        $roomTicketsJS = json_encode($roomTickets);
        return <<<JS
        {
                chart: {
                    type: 'bar',
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
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 6,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                series: [{
                    name: 'Số vé bán',
                    data: $roomTicketsJS
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
                yaxis: {
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '11px'
                        },
                        maxWidth: 150
                    }
                },
                colors: ['#17A2B8'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'horizontal',
                        shadeIntensity: 0.3,
                        gradientToColors: ['#20C997'],
                        inverseColors: false,
                        opacityFrom: 0.9,
                        opacityTo: 0.6,
                        stops: [0, 100]
                    }
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
                    y: {
                        formatter: function(value) {
                            return value + ' vé';
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val + ' vé';
                    },
                    style: {
                        colors: ['#ffffff']
                    }
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
        return "updateDataRoomMoviesData";
    }

    public function compileJavascript(){
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
