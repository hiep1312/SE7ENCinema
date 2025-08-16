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

    protected function queryData(?string $filter = null)
    {
        $bookings = Booking::whereHas('showtime', function ($q) {
            $q->where('movie_id', $this->movie->id);
        })->with(['showtime.room', 'foodOrderItems', 'user'])
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc');
        $bookingChart = Booking::whereHas('showtime', function ($q) {
            $q->where('movie_id', $this->movie->id);
        })->with(['showtime.room'])->get();
        /* Viết truy vấn CSDL tại đây */
        $fromCheckinChart = Carbon::now()->subDays(3);
        if ($fromCheckinChart) {
            $totalCount = (clone $bookings)->where('status', 'paid')->where('created_at', '>=', $fromCheckinChart)->count();
            $showtime = (clone $bookingChart)->pluck('showtime')->where('start_time', '>=', $fromCheckinChart)->pluck('room');
            $caps = $showtime->sum('capacity');
            return $result = [
                'totalCount' => $totalCount,
                'caps' => $caps,
            ];
        }
    }

    public function loadData(?string $filter = null)
    {
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement()
    {
        return "document.getElementById('checkinChart')";
    }

    protected function buildChartConfig()
    {
        /* Viết cấu hình biểu đồ tại đây */
        $totalCount = json_encode($this->data['totalCount']);
        $caps = json_encode($this->data['caps']);
        return <<<JS
        {
            series: [$totalCount,$caps],
            chart: {
                type: 'pie',
                height: 400,
                background: 'transparent',
                toolbar: { show: true },
                animations: {
                    enabled: false,
                },
            },
            labels: ['Số vé đã bán', 'Số vé còn lại'],
            colors: ['#34A853', '#FBBC04'],
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
                    donut: { size: '0%' }
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
                    fillColors: ['#34A853', '#FBBC04'],
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
                else console.log(window.{$optionsText});
                        console.log(window.{$chartText});
                        console.log({$ctxText});
                        console.log({$optionsText});
                        
                        
                        
                (window.{$optionsText} = {$this->buildChartConfig()}) &&  (window.{$chartText} = createScChart({$ctxText}, {$optionsText}));
            }
        });
        JS;
    }
}
