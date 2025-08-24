<?php

namespace App\Charts\Booking;

use App\Models\Booking;

class Revenue {
    protected $data;

    protected function queryData(?string $filter = null){
        $query = Booking::where('status', 'paid');
        $startDate = now()->subDays(2)->startOfDay();
                $endDate = now()->endOfDay();

                $data = $query->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue, COUNT(*) as bookings')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                $labels = [];
                $revenueData = [];
                $bookingsData = [];
                $avgRevenueData = [];

                for ($i = 0; $i < 3; $i++) {
                    $currentDate = now()->subDays(2 - $i)->format('Y-m-d');
                    $dateLabel = now()->subDays(2 - $i)->format('d/m');

                    $dayData = $data->where('date', $currentDate)->first();

                    $labels[] = $dateLabel;
                    $revenueData[] = $dayData ? $dayData->revenue : 0;
                    $bookingsData[] = $dayData ? $dayData->bookings : 0;
                    $avgRevenueData[] = $dayData && $dayData->bookings > 0 ? round($dayData->revenue / $dayData->bookings) : 0;
                }

                return [
                    'labels' => $labels,
                    'revenue' => $revenueData,
                    'bookings' => $bookingsData,
                    'avgRevenue' => $avgRevenueData
                ];
    }

    public function loadData(?string $filter = null){
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement(){
        return "document.getElementById('revenueChart')";
    }

    protected function buildChartConfig(){
        /* Viết cấu hình biểu đồ tại đây */
        $labelsJson = json_encode($this->data['labels']);
        $revenueDataJson = json_encode($this->data['revenue']);
        $bookingsDataJson = json_encode($this->data['bookings']);
        $avgRevenueDataJson = json_encode($this->data['avgRevenue']);

        return <<<JS
        {
            chart: {
                    height: 400,
                    type: 'line',
                    stacked: false,
                    background: 'transparent',
                    toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                            download: true,
                            selection: true,
                            pan: true,
                            reset: true
                        },
                        export: {
                            csv: {
                                filename: 'doanh-thu',
                                columnDelimiter: ',',
                                headerCategory: 'Ngày',
                                headerValue: 'Doanh thu',
                                categoryFormatter: function(x) {
                                    return x;
                                },
                                valueFormatter: function(y) {
                                    return new Intl.NumberFormat('vi-VN').format(y);
                                }
                            },
                            svg: {
                                filename: 'doanh-thu',
                            },
                            png: {
                                filename: 'doanh-thu',
                            }
                        },
                        autoSelected: 'zoom'
                    },
                    zoom: {
                        enabled: false,
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
                },
                tooltip: {
                    theme: 'dark',
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const dates = $labelsJson;
                        const revenueData = $revenueDataJson;
                        const bookingsData = $bookingsDataJson;

                        const ngay = dates[dataPointIndex] || '';
                        const doanhThu = revenueData[dataPointIndex] || 0;
                        const donHang = bookingsData[dataPointIndex] || 0;

                        return `
                        <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 320px;">
                            <div class="d-flex align-items-center mb-3">
                                <span class="fs-5 me-2">📅</span>
                                <h6 class="mb-0 text-white fw-bold">\${ngay}</h6>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-warning">💰 Doanh thu:</span>
                                <span class="fw-bold fs-6 text-warning">\${new Intl.NumberFormat('vi-VN').format(doanhThu)}đ</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-info">🛒 Số đơn hàng:</span>
                                <span class="fw-bold fs-6 text-info">\${donHang}</span>
                            </div>
                        </div>
                    `;
                    }
                },
                dataLabels: {
                    enabled: false,
                },
                colors: ['#FF1654', '#247BA0'],
                series: [{
                        name: 'Doanh thu (VND)',
                        data: $revenueDataJson
                    },
                    {
                        name: 'Số đơn hàng',
                        data: $bookingsDataJson
                    }
                ],
                stroke: {
                    width: [4, 4],
                    curve: 'smooth'
                },
                plotOptions: {
                    bar: {
                        columnWidth: '20%'
                    }
                },
                xaxis: {
                    categories: $labelsJson,
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '12px'
                        },
                        rotate: -45,
                        rotateAlways: false,
                        maxHeight: 60
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: [{
                        axisTicks: {
                            show: true
                        },
                        axisBorder: {
                            show: true,
                            color: '#FF1654'
                        },
                        labels: {
                            style: {
                                colors: '#FF1654',
                                fontSize: '12px'
                            },
                            formatter: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value);
                            }
                        },
                        title: {
                            text: 'Doanh thu (VND)',
                            style: {
                                color: '#FF1654'
                            }
                        }
                    },
                    {
                        opposite: true,
                        axisTicks: {
                            show: true
                        },
                        axisBorder: {
                            show: true,
                            color: '#247BA0'
                        },
                        labels: {
                            style: {
                                colors: '#247BA0',
                                fontSize: '12px'
                            },
                            formatter: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value);
                            }
                        },
                        title: {
                            text: 'Số đơn hàng',
                            style: {
                                color: '#247BA0'
                            }
                        }
                    }
                ],
                legend: {
                    horizontalAlign: 'left',
                    offsetX: 40,
                    labels: {
                        colors: '#ffffff'
                    }
                },
                grid: {
                    show: true,
                    borderColor: '#2d3748',
                    strokeDashArray: 0,
                    position: 'back'
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
        return "updateDataChartBooking";
    }

    public function compileJavascript(){
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
