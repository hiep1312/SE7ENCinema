<?php

namespace App\Charts\dashboard;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionHistoryChart {
    protected $data;

    protected function queryData(?array $filter = null){
        /* Viết truy vấn CSDL tại đây */
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();
        
        // Lấy dữ liệu khách hàng theo giới tính
        $genderData = User::select('gender', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('gender')
            ->orderBy('count', 'desc')
            ->get();

        // Lấy dữ liệu khách hàng theo độ tuổi chi tiết hơn
        $ageData = User::select(
            DB::raw('CASE 
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) < 13 THEN "Dưới 13 tuổi"
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 13 AND 17 THEN "13-17 tuổi"
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 18 AND 22 THEN "18-22 tuổi"
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 23 AND 27 THEN "23-27 tuổi"
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 28 AND 35 THEN "28-35 tuổi"
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 36 AND 45 THEN "36-45 tuổi"
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 46 AND 55 THEN "46-55 tuổi"
                WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 56 AND 65 THEN "56-65 tuổi"
                ELSE "Trên 65 tuổi"
            END as age_group'),
            DB::raw('COUNT(*) as count')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('birthday')
            ->groupBy('age_group')
            ->orderBy('count', 'desc')
            ->get();

        // Kết hợp dữ liệu theo thứ tự: Giới tính trước, sau đó đến độ tuổi
        $chartData = collect();
        
        // Thêm dữ liệu giới tính (hàng đầu tiên)
        foreach ($genderData as $item) {
            $label = match($item->gender) {
                'man' => 'Nam',
                'woman' => 'Nữ',
                'other' => 'Khác',
                default => 'Không xác định'
            };
            $chartData->push([
                'name' => $label,
                'value' => (int) $item->count,
                'category' => 'gender',
                'order' => 1 // Hàng đầu tiên
            ]);
        }

        // Thêm dữ liệu độ tuổi (hàng thứ hai)
        foreach ($ageData as $item) {
            $chartData->push([
                'name' => $item->age_group,
                'value' => (int) $item->count,
                'category' => 'age',
                'order' => 2 // Hàng thứ hai
            ]);
        }

        // Sắp xếp: Giới tính trước, sau đó đến độ tuổi, mỗi nhóm sắp xếp theo số lượng giảm dần
        $sortedData = $chartData->sortBy([
            ['order', 'asc'],
            ['value', 'desc']
        ])->values()->toArray();

        // Handle case where no data is found for the period
        if (empty($sortedData)) {
            return [
                ['name' => 'Không có dữ liệu', 'value' => 1, 'category' => 'none', 'order' => 0]
            ];
        }

        return $sortedData;
    }

    public function loadData(?array $filter = null){
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement(){
        return "document.getElementById('transactionHistoryChart')";
    }

    protected function buildChartConfig(){
        /* Viết cấu hình biểu đồ tại đây */
        $transactionHistoryData = json_encode($this->data);
        
        $genderData = array_filter($this->data, fn($item) => $item['category'] === 'gender');
        $ageData = array_filter($this->data, fn($item) => $item['category'] === 'age');
        
        $genderTotal = array_sum(array_column($genderData, 'value'));
        $ageTotal = array_sum(array_column($ageData, 'value'));
        
        return <<<JS
        {
            chart: {
                type: 'donut',
                height: 420,
                background: 'transparent',
                toolbar: {
                    show: true
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                },
            },
            series: JSON.parse('$transactionHistoryData').map(item => item.value),
            labels: JSON.parse('$transactionHistoryData').map(item => item.name),
            colors: [
                // Màu cho giới tính (vòng trong) - tông xanh dương
                '#3B82F6', '#1E40AF', '#6366F1',
                // Màu cho độ tuổi (vòng ngoài) - tông đa dạng
                '#F59E0B', '#EF4444', '#06B6D4', '#8B5CF6', '#10B981', '#EC4899', '#8B5A2B', '#F97316', '#84CC16'
            ],
            stroke: {
                show: true,
                width: 3,
                colors: ['#111827']
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'radial',
                    shadeIntensity: 0.4,
                    opacityFrom: 0.95,
                    opacityTo: 0.85,
                    stops: [0, 70, 100]
                }
            },
            states: {
                hover: {
                    filter: {
                        type: 'lighten',
                        value: 0.12
                    }
                },
                active: {
                    filter: {
                        type: 'lighten',
                        value: 0.2
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    expandOnClick: true,
                    donut: {
                        size: '45%',
                        background: 'transparent',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '16px',
                                fontWeight: 600,
                                color: '#FFFFFF',
                                offsetY: -10
                            },
                            value: {
                                show: true,
                                fontSize: '24px',
                                fontWeight: 700,
                                color: '#3B82F6',
                                offsetY: 10,
                                formatter: function(val) {
                                    return val.toLocaleString('vi-VN');
                                }
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'Tổng khách hàng',
                                fontSize: '14px',
                                fontWeight: 500,
                                color: '#9CA3AF',
                                formatter: function(w) {
                                    const genderData = w.globals.series.slice(0, 3);
                                    const genderTotal = genderData.reduce((a, b) => a + b, 0);
                                    return genderTotal.toLocaleString('vi-VN');
                                }
                            }
                        }
                    }
                }
            },
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '12px',
                fontWeight: 500,
                labels: {
                    colors: '#ffffff'
                },
                markers: {
                    width: 12,
                    height: 12,
                    radius: 3
                },
                itemMargin: {
                    horizontal: 8,
                    vertical: 4
                },
                formatter: function(seriesName, opts) {
                    const value = opts.w.globals.series[opts.seriesIndex] || 0;
                    const category = JSON.parse('$transactionHistoryData')[opts.seriesIndex]?.category || 'other';
                    
                    let groupTotal = 1;
                    if (category === 'gender') {
                        groupTotal = $genderTotal;
                    } else if (category === 'age') {
                        groupTotal = $ageTotal;
                    }
                    
                    const pct = Math.round((value / groupTotal) * 100);
                    
                    // Thêm icon để phân biệt nhóm
                    let prefix = '';
                    if (category === 'gender') prefix = ' ';
                    else if (category === 'age') prefix = ' ';
                    
                    return prefix + seriesName + ': ' + value.toLocaleString('vi-VN') + ' (' + pct + '%)';
                }
            },
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '14px',
                    fontFamily: 'Inter, system-ui, sans-serif'
                },
                custom: function({seriesIndex, w}) {
                    const name = w.config.labels[seriesIndex] || '';
                    const value = (w.globals.series[seriesIndex] || 0);
                    const category = JSON.parse('$transactionHistoryData')[seriesIndex]?.category || 'other';
                    
                    let groupTotal = 1;
                    let categoryTitle = 'Phân loại';
                    let icon = '';
                    let bgColor = 'rgba(59, 130, 246, 0.1)';
                    let borderColor = 'rgba(59, 130, 246, 0.2)';
                    let textColor = '#93C5FD';
                    
                    if (category === 'gender') {
                        groupTotal = $genderTotal;
                        categoryTitle = 'Giới tính';
                        icon = '';
                        bgColor = 'rgba(99, 102, 241, 0.1)';
                        borderColor = 'rgba(99, 102, 241, 0.2)';
                        textColor = '#A5B4FC';
                    } else if (category === 'age') {
                        groupTotal = $ageTotal;
                        categoryTitle = 'Độ tuổi';
                        icon = '';
                        bgColor = 'rgba(245, 158, 11, 0.1)';
                        borderColor = 'rgba(245, 158, 11, 0.2)';
                        textColor = '#FCD34D';
                    }
                    
                    const pct = Math.round((value / groupTotal) * 100);
                    const genderDatas = w.globals.series.slice(0, 3);
                    const totalCustomers = genderDatas.reduce((a, b) => a + b, 0);
                    return '<div style="' +
                           'padding: 20px; ' +
                           'background: linear-gradient(135deg, rgba(17, 24, 39, 0.98), rgba(31, 41, 55, 0.95)); ' +
                           'border-radius: 16px; ' +
                           'min-width: 280px; ' +
                           'box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.05); ' +
                           'border: 1px solid rgba(75, 85, 99, 0.3); ' +
                           'backdrop-filter: blur(16px);' +
                           '">' +
                           
                           // Header với icon và category
                           '<div style="' +
                           'display: flex; ' +
                           'align-items: center; ' +
                           'margin-bottom: 16px; ' +
                           'padding-bottom: 12px; ' +
                           'border-bottom: 1px solid rgba(75, 85, 99, 0.3);' +
                           '">' +
                           '<span style="font-size: 24px; margin-right: 12px;">' + icon + '</span>' +
                           '<div style="flex: 1;">' +
                           '<div style="font-size: 12px; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 600;">' + categoryTitle + '</div>' +
                           '<div style="font-weight: 700; color: #FFFFFF; font-size: 18px; margin-top: 4px;">' + name + '</div>' +
                           '</div>' +
                           '</div>' +
                           
                           // Thông tin chi tiết với grid layout
                           '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">' +
                           
                           // Số lượng
                           '<div style="' +
                           'background: ' + bgColor + '; ' +
                           'padding: 14px; ' +
                           'border-radius: 12px; ' +
                           'border: 1px solid ' + borderColor + '; ' +
                           'text-align: center;' +
                           '">' +
                           '<div style="font-size: 11px; color: ' + textColor + '; margin-bottom: 6px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">SỐ LƯỢNG</div>' +
                           '<div style="font-size: 20px; font-weight: 800; color: #FFFFFF;">' + value.toLocaleString('vi-VN') + '</div>' +
                           '</div>' +
                           
                           // Tỷ lệ trong nhóm
                           '<div style="' +
                           'background: rgba(16, 185, 129, 0.1); ' +
                           'padding: 14px; ' +
                           'border-radius: 12px; ' +
                           'border: 1px solid rgba(16, 185, 129, 0.2); ' +
                           'text-align: center;' +
                           '">' +
                           '<div style="font-size: 11px; color: #6EE7B7; margin-bottom: 6px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">TỶ LỆ NHÓM</div>' +
                           '<div style="font-size: 20px; font-weight: 800; color: #FFFFFF;">' + pct + '%</div>' +
                           '</div>' +
                           
                           '</div>' +
                           // Footer với thông tin tổng quan
                           '<div style="' +
                           'margin-top: 16px; ' +
                           'padding-top: 12px; ' +
                           'border-top: 1px solid rgba(75, 85, 99, 0.3); ' +
                           'text-align: center;' +
                           '">'+
                           '<div style="font-size: 10px; color: #4B5563; margin-top: 2px;">Tổng: ' + totalCustomers.toLocaleString('vi-VN') + ' khách hàng</div>' +
                           '</div>' 
                           '</div>';
                }
            },
            responsive: [{
                    breakpoint: 992,
                    options: {
                        chart: {
                            height: 350
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '50%'
                                }
                            }
                        }
                    }
                },
                {
                    breakpoint: 576,
                    options: {
                        chart: {
                            height: 320
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '55%'
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
