<?php

namespace App\Charts\Showtime;

use App\Models\BookingSeat;
use App\Models\Showtime;

class SeatFillRateChart {
    protected $data = [];
    protected int $showtimeId = 0;

    protected function queryData(?string $filter = null){
        $this->showtimeId = (int)($filter ?? 0);
        $showtime = Showtime::with('room')->find($this->showtimeId);
        $capacity = (int)($showtime?->room?->capacity ?? 0);

        $sold = (int) BookingSeat::whereHas('booking', function($q){
                $q->where('showtime_id', $this->showtimeId)->where('status', 'paid');
            })->count();

        $remain = max(0, $capacity - $sold);
        $occupancy = $capacity > 0 ? round(($sold / $capacity) * 100, 1) : 0;

        return [
            'occupancy' => $occupancy,
            'capacity'  => $capacity,
            'sold'      => $sold,
            'remain'    => $remain,
        ];
    }

    public function loadData(?string $filter = null){
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement(){
        return "document.getElementById('stSeatFillRateChart')";
    }

    protected function buildChartConfig(){
        $occupancy = (float)($this->data['occupancy'] ?? 0);
        $capacity  = (int)($this->data['capacity'] ?? 0);
        $sold      = (int)($this->data['sold'] ?? 0);
        $remain    = (int)($this->data['remain'] ?? 0);

        return <<<JS
        {
            chart: {
                type: 'radialBar',
                height: 400,
                background: 'transparent',
                toolbar: {
                    show: true,
                    tools: { download: true, selection: true, pan: true, reset: true },
                    export: {
                        csv: { filename: 'ty-le-lap-day', headerCategory: 'Chỉ số', headerValue: 'Giá trị' },
                        svg: { filename: 'ty-le-lap-day' },
                        png: { filename: 'ty-le-lap-day' }
                    }
                },
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            series: [{$occupancy}],
            labels: ['Tỷ lệ lấp đầy'],
            colors: ['#34A853'],
            plotOptions: {
                radialBar: {
                    startAngle: -135,
                    endAngle: 135,
                    hollow: {
                        margin: 0,
                        size: '70%',
                        background: 'transparent',
                        dropShadow: { enabled: true, top: 3, left: 0, blur: 4, opacity: 0.24 }
                    },
                    track: {
                        background: '#2d3748',
                        strokeWidth: '67%',
                        margin: 0,
                        dropShadow: { enabled: true, top: -3, left: 0, blur: 4, opacity: 0.35 }
                    },
                    dataLabels: {
                        show: true,
                        name: { show: true, color: '#ffffff', fontSize: '14px', offsetY: -12 },
                        value: {
                            show: true,
                            color: '#ffffff',
                            fontSize: '32px',
                            formatter: function(val){ return parseFloat(val).toFixed(1) + '%'; }
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
                    gradientToColors: ['#1e7e34'],
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            stroke: { lineCap: 'round' },
            tooltip: {
                theme: 'dark',
                custom: function({series}){
                    const percent = (series?.[0] ?? 0).toFixed(1);
                    const capacity = {$capacity};
                    const sold = {$sold};
                    const remain = {$remain};
                    return `
                        <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 300px;">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white">📊 Tỷ lệ lấp đầy</span>
                                <span class="fw-bold text-success">\${percent}%</span>
                            </div>
                            <hr class="text-secondary my-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white">🪑 Sức chứa</span>
                                <span class="fw-bold text-white">\${new Intl.NumberFormat('vi-VN').format(capacity)} ghế</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-success">🎟️ Đã bán</span>
                                <span class="fw-bold text-success">\${new Intl.NumberFormat('vi-VN').format(sold)} ghế</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-warning">🪑 Còn lại</span>
                                <span class="fw-bold text-warning">\${new Intl.NumberFormat('vi-VN').format(remain)} ghế</span>
                            </div>
                        </div>
                    `;
                }
            },
            legend: { show: false }
        }
        JS;
    }

    public function getFilterText(string $filterValue){ return 'N/A'; }
    public function getChartConfig(){ return $this->buildChartConfig(); }
    public function getData(){ return $this->data; }
    public function getEventName(){ return "updateDataSeatFillRateChart"; }

    public function compileJavascript(){
        $ctxText = "ctxSeatFillRateChart";
        $optionsText = "optionsSeatFillRateChart";
        $chartText = "chartSeatFillRateChart";
        echo <<<JS
        Livewire.on("{$this->getEventName()}", async function ([data]){
            await new Promise(resolve => setTimeout(resolve));
            const {$ctxText} = {$this->bindDataToElement()};
            if($ctxText){
                if(window.{$chartText} && document.contains(window.{$chartText}.getElement())) (window.{$optionsText} = new Function("return " + data)()) && (window.{$chartText}.updateOptions(window.{$optionsText}));
                else (window.{$optionsText} = {$this->buildChartConfig()}) && (window.{$chartText} = createScChart({$ctxText}, {$optionsText}));
            }
        });
        JS;
    }
}