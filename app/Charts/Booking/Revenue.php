<?php

namespace App\Charts\Booking;

use App\Models\Booking;

class Revenue
{
    protected $data;
    protected $booking;
    public function __construct(int $booking)
    {
        $this->booking = Booking::with('showtime.movie', 'showtime.room', 'user', 'seats', 'promotionUsage', 'foodOrderItems.variant.foodItem', 'foodOrderItems.variant.attributeValues.attribute')->findOrFail($booking);
    }
    protected function queryData(?string $filter = null)
    {
        $seatCount = $this->booking->seats->groupBy('seat_type')->map(function ($seats) {
            return $seats->count();
        })->toArray();
        $totalBooking = $this->booking->seats->count();
        return [
            'totalBooking' => $totalBooking,
            'seatCount' => $seatCount,
        ];
    }

    public function loadData(?string $filter = null)
    {
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement()
    {
        return "document.getElementById('revenueChart')";
    }

    protected function buildChartConfig()
    {
        /* Viết cấu hình biểu đồ tại đây */
        $vipSeats = $this->data['seatCount']['vip'] ?? 0;
        $standardSeats = $this->data['seatCount']['standard'] ?? 0;
        $coupleSeats = $this->data['seatCount']['couple'] ?? 0;
        $vipSeatsJS = json_encode($vipSeats);
        $standardSeatsJS = json_encode($standardSeats);
        $coupleSeatsJS = json_encode($coupleSeats);
        return <<<JS
        {
            series: [$vipSeatsJS, $standardSeatsJS, $coupleSeatsJS],
            labels: ['Ghế VIP','Ghế thường','Ghế đôi'],
            chart: {
                type: 'donut',
                height: 400,
                background: 'transparent',
                toolbar: { show: true },
                animations: {
                    enabled: false,
                },
            },
            colors: ['#FBBC04', '#888888ff', '#f105b2ff'],
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
                    fillColors: ['#FBBC04', '#898371ff', '#d0069aff'],
                    radius: 3
                }
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
        return "updateDataChartBooking";
    }

    public function compileJavascript()
    {
        $ctxText = "ctxChartBooking";
        $optionsText = "optionsChartBooking";
        $chartText = "chartChartBooking";
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
