<?php

namespace App\Charts\dashboard;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class TransactionHistoryChart {
    protected $data;

    protected function queryData(?string $filter = null){
        /* Viết truy vấn CSDL tại đây */
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();
         $data = Booking::select('payment_method', DB::raw('COUNT(*) as transaction_count'))
            ->where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('payment_method')
            ->get();

        $paymentMethodLabels = [
            'credit_card' => 'Thẻ tín dụng',
            'e_wallet' => 'Ví điện tử',
            'cash' => 'Tiền mặt',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
        ];

        $chartData = [];
        foreach ($data as $item) {
            $label = $paymentMethodLabels[$item->payment_method] ?? ucfirst(str_replace('_', ' ', $item->payment_method));
            $chartData[] = [
                'name' => $label,
                'value' => (int) $item->transaction_count,
            ];
        }

        // Handle case where no data is found for the period
        if (empty($chartData)) {
            return [
                ['name' => 'Không có dữ liệu', 'value' => 1] // Placeholder for empty chart
            ];
        }

        return $chartData;
    }

    public function loadData(?string $filter = null){
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement(){
        return "document.getElementById('transactionHistoryChart')";
    }

    protected function buildChartConfig(){
        /* Viết cấu hình biểu đồ tại đây */
        $transactionHistoryData = json_encode($this->data);
        
        return <<<JS
        {
            chart: {
                type: 'pie',
                height: 380,
                background: 'transparent',
                toolbar: {
                    show: true
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 700
                }
            },
            series: JSON.parse('$transactionHistoryData').map(item => item.value),
            labels: JSON.parse('$transactionHistoryData').map(item => item.name),
            colors: [
                '#6366F1', '#8B5CF6', '#EC4899', '#F59E0B',
                '#EF4444', '#06B6D4', '#A78BFA'
            ],
            stroke: {
                show: true,
                width: 2,
                colors: ['#111827']
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'vertical',
                    shadeIntensity: 0.35,
                    opacityFrom: 0.95,
                    opacityTo: 0.85,
                    stops: [0, 90, 100]
                }
            },
            states: {
                hover: {
                    filter: {
                        type: 'lighten',
                        value: 0.08
                    }
                },
                active: {
                    filter: {
                        type: 'lighten',
                        value: 0.15
                    }
                }
            },
            // Ẩn nhãn phần trăm trên lát cắt
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    expandOnClick: true,
                    donut: {
                        size: '70%',
                        background: 'transparent',
                        labels: {
                            show: false
                        }
                    }
                }
            },
            // Hiển thị legend để người dùng thấy loại thanh toán (tên) luôn hiển thị
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '12px',
                labels: {
                    colors: '#ffffff'
                },
                markers: {
                    width: 10,
                    height: 10,
                    radius: 2
                },
                formatter: function(seriesName, opts) {
                    const value = opts.w.globals.series[opts.seriesIndex] || 0;
                    const total = opts.w.globals.seriesTotals.reduce((a, b) => a + b, 0) || 1;
                    const pct = Math.round((value / total) * 100);
                    return seriesName + ': ' + value.toLocaleString('vi-VN') + ' (' + pct + '%)';
                }
            },
            tooltip: {
                theme: 'dark',
                custom: function({seriesIndex, w}) {
                    const name = w.config.labels[seriesIndex] || '';
                    const value = (w.globals.series[seriesIndex] || 0);
                    const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0) || 1;
                    const pct = Math.round((value / total) * 100);
                    const iconMap = {
                        'Thẻ tín dụng': '💳',
                        'Ví điện tử': '📱',
                        'Tiền mặt': '💵',
                        'Chuyển khoản ngân hàng': '🏦'
                    };
                    const icon = iconMap[name] || '💠';
                    return '<div style="padding:12px;background:rgba(0,0,0,0.95);border-radius:8px;min-width:220px;">' +
                           '<div style="font-weight:700;color:#fff;margin-bottom:8px">' + icon + ' ' + name + '</div>' +
                           '<div style="display:flex;justify-content:space-between;color:#ddd">' +
                           '<span>Giao dịch:</span>' +
                           '<strong style="color:#fff">' + value.toLocaleString('vi-VN') + '</strong>' +
                           '</div>' +
                           '<div style="display:flex;justify-content:space-between;color:#ddd;margin-top:6px">' +
                           '<span>Tỷ lệ:</span>' +
                           '<strong style="color:#fff">' + pct + '%</strong>' +
                           '</div>' +
                           '</div>';
                }
            },
            responsive: [{
                    breakpoint: 992,
                    options: {
                        chart: {
                            height: 300
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '68%'
                                }
                            }
                        }
                    }
                },
                {
                    breakpoint: 576,
                    options: {
                        chart: {
                            height: 280
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '62%'
                                }
                            }
                        }
                    }
                }
            ]
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
        return "updateDataTransactionHistoryChart";
    }

    public function compileJavascript(){
        $ctxText = "ctxTransactionHistoryChart";
        $optionsText = "optionsTransactionHistoryChart";
        $chartText = "chartTransactionHistoryChart";
        echo <<<JS
        const {$ctxText} = {$this->bindDataToElement()};
        window.{$optionsText} = {$this->buildChartConfig()};

        window.{$chartText} = createScChart({$ctxText}, {$optionsText});

        Livewire.on("{$this->getEventName()}", function ([data]){
            window.{$optionsText} = new Function("return " + data)();
            if(window.{$chartText}) window.{$chartText}.updateOptions(window.{$optionsText});
        });
        JS;
    }
}