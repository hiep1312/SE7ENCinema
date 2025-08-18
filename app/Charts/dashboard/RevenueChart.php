<?php

namespace App\Charts\dashboard;

use App\Models\Booking;
use Carbon\Carbon;

class RevenueChart
{
    protected $data;

    protected function queryData(?string $filter = null)
    {
        /* Viết truy vấn CSDL tại đây */

        $now = Carbon::now();
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);

            $revenue = Booking::where('status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('total_price');
            $bookings = Booking::where('status', 'paid')
                ->whereDate('created_at', $date)
                ->count();

            $data[] = [
                'x' => $date->format('d/m'),
                'y' => $revenue,
                'bookings' => $bookings
            ];
        }

        return $data;
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
        if (empty($this->data)) {
            $this->loadData();
        }
        $chartData = collect($this->data);
        $labels = $chartData->map(fn($item) => $item['x'])->toJson();
        $revenues = $chartData->map(fn($item) => $item['y'])->toJson();
        $bookings = $chartData->map(fn($item) => $item['bookings'])->toJson();

        return <<<JS
    {
        series: [{
            name: 'Doanh thu (VNĐ)',
            data: {$revenues}
        }],
        chart: {
            type: 'area',
            height: 450,
            background: 'transparent',
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                    reset: true
                },
                export: {
                    csv: {
                        filename: 'doanh-thu-se7en-cinema',
                        columnDelimiter: ',',
                        headerCategory: 'Thời gian',
                        headerValue: 'Doanh thu (VNĐ)',
                    },
                    svg: {
                        filename: 'doanh-thu-se7en-cinema',
                    },
                    png: {
                        filename: 'doanh-thu-se7en-cinema',
                    }
                }
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
            },
            zoom: {
                enabled: true,
                type: 'x',
                autoScaleYaxis: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        colors: ['#007bff'],
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                type: 'vertical',
                shadeIntensity: 0.3,
                gradientToColors: ['#0056b3'],
                inverseColors: false,
                opacityFrom: 0.9,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: {$labels},
            labels: {
                style: {
                    colors: '#e2e8f0',
                    fontSize: '12px',
                    fontWeight: 500
                },
                rotate: -45,
                rotateAlways: false
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            title: {
                text: 'Thời gian',
                style: {
                    color: '#e2e8f0',
                    fontSize: '14px',
                    fontWeight: 600
                }
            }
        },
        yaxis: {
            title: {
                text: 'Doanh thu (VNĐ)',
                style: {
                    color: '#e2e8f0',
                    fontSize: '14px',
                    fontWeight: 600
                }
            },
            labels: {
                style: {
                    colors: '#e2e8f0',
                    fontSize: '12px',
                    fontWeight: 500
                },
                formatter: function(value) {
                    return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                }
            }
        },
        grid: {
            show: true,
            borderColor: '#2d3748',
            strokeDashArray: 1,
            xaxis: {
                lines: {
                    show: false
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            },
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            labels: {
                colors: '#e2e8f0'
            },
            markers: {
                width: 12,
                height: 12,
                radius: 6
            }
        },
        tooltip: {
            theme: 'dark',
            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                const labels = {$labels};
                const revenues = {$revenues};
                const bookings = {$bookings};

                const x = labels[dataPointIndex] || '';
                const revenue = revenues[dataPointIndex] || 0;
                const booking = bookings[dataPointIndex] || 0;

                let phanTramTang = 0;
                let iconTangTruong = '📊';
                let mauTangTruong = '#6c757d';
                if (dataPointIndex > 0) {
                    const doanhThuTruoc = w.config.series[0].data[dataPointIndex - 1].y || 0;
                    if (doanhThuTruoc > 0) {
                        phanTramTang = ((revenue - doanhThuTruoc) / doanhThuTruoc * 100).toFixed(1);
                        if (phanTramTang > 0) {
                            iconTangTruong = '📈';
                            mauTangTruong = '#28a745';
                        } else if (phanTramTang < 0) {
                            iconTangTruong = '📉';
                            mauTangTruong = '#dc3545';
                        }
                    }
                }
                return `
                    <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 320px; max-width: 400px;">
                        <div class="d-flex align-items-center mb-3">
                            <span class="fs-4 me-2">💰</span>
                            <h6 class="mb-0 text-white fw-bold">\${x}</h6>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-primary bg-opacity-10 rounded">
                                    <span class="text-primary fw-medium">
                                        💸
                                        Doanh thu:
                                    </span>
                                    <span class="fw-bold fs-6 text-primary">
                                        \${new Intl.NumberFormat('vi-VN').format(revenue)}đ
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-success bg-opacity-10 rounded">
                                    <span class="text-primary fw-medium">
                                        🛒
                                        Đơn hàng:
                                    </span>
                                    <span class="fw-bold text-success">\${booking}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-info bg-opacity-10 rounded">
                                    <span class="text-info fw-medium">
                                        ✅
                                        TB/đơn:
                                    </span>
                                    <span class="fw-bold text-info">\${new Intl.NumberFormat('vi-VN').format(revenue / (booking || 1))}đ</span>
                                </div>
                            </div>
                        </div>
                        \${dataPointIndex > 0 ? `
                            <div class="border-top border-secondary pt-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span style="color: \${mauTangTruong}" class="fw-medium">
                                        \${iconTangTruong} So với trước:
                                    </span>
                                    <span class="fw-bold" style="color: \${mauTangTruong}">
                                        \${phanTramTang >= 0 ? '+' : ''}\${phanTramTang}%
                                    </span>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `;
            }
        },
        responsive: [{
            breakpoint: 768,
            options: {
                chart: {
                    height: 350
                },
                xaxis: {
                    labels: {
                        rotate: -45,
                        style: {
                            fontSize: '10px'
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
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
        return "updateDatarevenueChart";
    }

    public function compileJavascript()
    {
        $ctxText = "ctxrevenueChart";
        $optionsText = "optionsrevenueChart";
        $chartText = "chartrevenueChart";
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