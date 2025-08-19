<?php

namespace App\Charts\Showtime;

use App\Models\Booking;
use App\Models\FoodOrderItem;
use App\Models\BookingSeat;

class RevenueTicketFoodChart {
    protected $data = [];
    protected int $showtimeId = 0;

    protected function queryData(?string $filter = null){
        $this->showtimeId = (int)($filter ?? 0);

        // Tính tổng doanh thu từ Booking
        $totalRevenue = (int) Booking::where('showtime_id', $this->showtimeId)
            ->where('status', 'paid')
            ->sum('total_price');

        // Tính doanh thu đồ ăn
        $foodRevenue = (int) FoodOrderItem::whereHas('booking', function($q){
                $q->where('showtime_id', $this->showtimeId)->where('status', 'paid');
            })->selectRaw('COALESCE(SUM(quantity * price), 0) as total')->value('total');

        // Doanh thu vé = Tổng doanh thu - Doanh thu đồ ăn
        $ticketRevenue = $totalRevenue - $foodRevenue;

        // Tính số lượng vé và đồ ăn
        $ticketCount = (int) BookingSeat::whereHas('booking', function($q){
                $q->where('showtime_id', $this->showtimeId)->where('status', 'paid');
            })->count();

        $foodCount = (int) FoodOrderItem::whereHas('booking', function($q){
                $q->where('showtime_id', $this->showtimeId)->where('status', 'paid');
            })->sum('quantity');

        return [
            'labels' => ['Vé', 'Đồ ăn'],
            'series' => [ $ticketRevenue, $foodRevenue ],
            'total' => $totalRevenue,
            'ticketCount' => $ticketCount,
            'foodCount' => $foodCount,
        ];
    }

    public function loadData(?string $filter = null){
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement(){
        return "document.getElementById('stRevenueTicketFoodChart')";
    }

    protected function buildChartConfig(){
        $labels = json_encode($this->data['labels'] ?? []);
        $values = json_encode($this->data['series'] ?? []);
        $total = (int)($this->data['total'] ?? 0);
        $ticketCount = (int)($this->data['ticketCount'] ?? 0);
        $foodCount = (int)($this->data['foodCount'] ?? 0);
        
        return <<<JS
        {
            chart: {
                type: 'bar',
                height: 320,
                background: 'transparent',
                toolbar: {
                    show: true,
                    tools: { download: true, selection: true, pan: true, reset: true },
                    export: {
                        csv: { filename: 'doanh-thu-ve-do-an', headerCategory: 'Nhóm', headerValue: 'Doanh thu' },
                        svg: { filename: 'doanh-thu-ve-do-an' },
                        png: { filename: 'doanh-thu-ve-do-an' }
                    }
                },
                zoom: { enabled: false },
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            series: [{ name: 'Doanh thu', data: $values }],
            colors: ['#8AB4F8', '#FF6B6B'],
            plotOptions: { bar: { horizontal: false, columnWidth: '45%', borderRadius: 6 } },
            dataLabels: { enabled: false },
            xaxis: { categories: $labels, labels: { style: { colors: '#adb5bd' } } },
            yaxis: { labels: { style: { colors: '#adb5bd' }, formatter: val => new Intl.NumberFormat('vi-VN').format(val) + 'đ' } },
            legend: { labels: { colors: '#f8f9fa' } },
            tooltip: {
                theme: 'dark',
                custom: function({series, seriesIndex, dataPointIndex, w}){
                    const cats = w.config.xaxis.categories || [];
                    const val = (series[0]||[])[dataPointIndex] || 0;
                    const total = (series[0]||[]).reduce((a,b)=>a+b,0) || $total;
                    const pct = total ? ((val/total)*100).toFixed(1) : 0;
                    const ticketCount = $ticketCount;
                    const foodCount = $foodCount;
                    
                    let countInfo = '';
                    if (dataPointIndex === 0) {
                        countInfo = `<div class="d-flex justify-content-between mb-2">
                            <span class="text-info">🎫 Số lượng vé:</span>
                            <span class="fw-bold text-info">\${ticketCount}</span>
                        </div>`;
                    } else {
                        countInfo = `<div class="d-flex justify-content-between mb-2">
                            <span class="text-info">🍽️ Số lượng món:</span>
                            <span class="fw-bold text-info">\${foodCount}</span>
                        </div>`;
                    }
                    
                    return `
                        <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 280px;">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white">📦 Nhóm</span>
                                <span class="fw-bold text-white">\${cats[dataPointIndex] || ''}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-warning">💰 Doanh thu</span>
                                <span class="fw-bold text-warning">\${new Intl.NumberFormat('vi-VN').format(val)}đ</span>
                            </div>
                            \${countInfo}
                            <hr class="text-secondary my-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-warning">📊 Tỉ trọng</span>
                                <span class="fw-bold text-warning">\${pct}%</span>
                            </div>
                        </div>
                    `;
                }
            }
        }
        JS;
    }

    public function getFilterText(string $filterValue){ return 'N/A'; }
    public function getChartConfig(){ return $this->buildChartConfig(); }
    public function getData(){ return $this->data; }
    public function getEventName(){ return "updateDataRevenueTicketFoodChart"; }

    public function compileJavascript(){
        $ctxText = "ctxRevenueTicketFoodChart";
        $optionsText = "optionsRevenueTicketFoodChart";
        $chartText = "chartRevenueTicketFoodChart";
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