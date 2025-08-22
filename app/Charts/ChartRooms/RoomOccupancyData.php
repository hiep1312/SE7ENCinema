<?php

namespace App\Charts\ChartRooms;

use App\Models\BookingSeat;
use App\Models\Showtime;
class RoomOccupancyData {
    protected $data;
    protected $room;

    public function __construct($room) {
        $this->room = $room;
    }
    protected function queryData(?string $filter = null){
        $startDate = now()->subDays(2)->startOfDay();
        $endDate = now()->endOfDay();

        $totalBooked = BookingSeat::join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->where('showtimes.room_id', $this->room->id)
            ->where('bookings.status', 'paid')
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->count();

        $totalShowtimes = Showtime::where('room_id', $this->room->id)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->count();

        $maxPossibleSeats = $this->room->capacity * max($totalShowtimes, 1);
        $occupancyRate = $maxPossibleSeats > 0 ? round(($totalBooked / $maxPossibleSeats) * 100, 1) : 0;
        $occupancyRate = min($occupancyRate, 100);

        return [
            'occupancy_rate' => $occupancyRate,
            'total_booked' => $totalBooked,
            'max_possible' => $maxPossibleSeats
        ];
    }

    public function loadData(?string $filter = null){
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement(){
        return "document.getElementById('occupancyChart')";
    }

    protected function buildChartConfig(){
        $roomOccupancyRate=$this->data['occupancy_rate'];
        $roomOccupancyRateJS = json_encode($roomOccupancyRate);
        return <<<JS
        {
            chart: {
                type: 'radialBar',
                height: 400,
                background: 'transparent',
                toolbar: {
                    show: true
                }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -135,
                    endAngle: 135,
                    hollow: {
                        margin: 0,
                        size: '70%',
                        background: 'transparent',
                        position: 'front',
                        dropShadow: {
                            enabled: true,
                            top: 3,
                            left: 0,
                            blur: 4,
                            opacity: 0.24
                        }
                    },
                    track: {
                        background: '#2d3748',
                        strokeWidth: '67%',
                        margin: 0,
                        dropShadow: {
                            enabled: true,
                            top: -3,
                            left: 0,
                            blur: 4,
                            opacity: 0.35
                        }
                    },
                    dataLabels: {
                        show: true,
                        name: {
                            offsetY: -10,
                            show: true,
                            color: '#ffffff',
                            fontSize: '17px'
                        },
                        value: {
                            color: '#ffffff',
                            fontSize: '36px',
                            show: true
                        }
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#4CAF50'],
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            series: [$roomOccupancyRateJS],
            labels: ['Tỷ lệ lấp đầy'],
            colors: ['#34A853']
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
        return "updateDataRoomOccupancyData";
    }

    public function compileJavascript(){
        $ctxText = "ctxRoomOccupancyData";
        $optionsText = "optionsRoomOccupancyData";
        $chartText = "chartRoomOccupancyData";
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
