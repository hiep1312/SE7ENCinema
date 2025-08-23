<?php

namespace App\Charts\dashboard;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueSourceChart {
    protected $data;
    protected function queryData(?array $filter = null){
        /* Viết truy vấn CSDL tại đây */
        is_array($filter) && [$fromDate, $rangeDays] = $filter;
        $rangeDays = (int) $rangeDays;
        $fromDate = $fromDate ? Carbon::parse($fromDate) : Carbon::now()->subDays($rangeDays);
        $toDate = $fromDate->copy()->addDays($rangeDays);

        $startDate = $fromDate->copy()->startOfDay();
        $endDate = $toDate->copy()->endOfDay();
        
        // Xác định định dạng thời gian dựa trên filter
        $groupFormat = '%Y-%m-%d';
        $labelFormat = 'd/m';
        $keyFormatPhp = 'Y-m-d';
        $dateStep = '1 day';

        // Doanh thu vé theo mốc thời gian
        $ticketRevenue = Booking::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '$groupFormat') as period"), 
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('COUNT(*) as booking_count')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');

        // Doanh thu đồ ăn theo mốc thời gian
        $foodRevenue = DB::table('food_order_items')
            ->join('bookings', 'food_order_items.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'paid')
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->select(
                DB::raw("DATE_FORMAT(bookings.created_at, '$groupFormat') as period"), 
                DB::raw('SUM(food_order_items.price * food_order_items.quantity) as revenue'),
                DB::raw('COUNT(DISTINCT bookings.id) as order_count')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');

        // Tạo dãy labels liên tục theo thời gian
        $labels = [];
        $ticketSeries = [];
        $foodSeries = [];
        $ticketCounts = [];
        $foodCounts = [];

        $cursor = $startDate->copy();
        while ($cursor->lte($endDate)) {
            $key = $cursor->format($keyFormatPhp);
            $labels[] = $cursor->format($labelFormat);
            
            // Doanh thu vé
            $ticketData = $ticketRevenue->get($key);
            $ticketSeries[] = (int)($ticketData ? $ticketData->revenue : 0);
            $ticketCounts[] = (int)($ticketData ? $ticketData->booking_count : 0);
            
            // Doanh thu đồ ăn
            $foodData = $foodRevenue->get($key);
            $foodSeries[] = (int)($foodData ? $foodData->revenue : 0);
            $foodCounts[] = (int)($foodData ? $foodData->order_count : 0);

            $cursor->addDay();
        }

        // Nếu tất cả đều 0 thì trả về placeholder tránh lỗi chart rỗng
        if (array_sum($ticketSeries) === 0 && array_sum($foodSeries) === 0) {
            return [
                'labels' => [now()->format('d/m')],
                'series' => [
                    [
                        'name' => 'Doanh thu vé', 
                        'data' => [0],
                        'counts' => [0]
                    ],
                    [
                        'name' => 'Doanh thu đồ ăn', 
                        'data' => [0],
                        'counts' => [0]
                    ],
                ]
            ];
        }

        return [
            'labels' => $labels,
            'series' => [
                [
                    'name' => 'Doanh thu vé', 
                    'data' => $ticketSeries,
                    'counts' => $ticketCounts
                ],
                [
                    'name' => 'Doanh thu đồ ăn', 
                    'data' => $foodSeries,
                    'counts' => $foodCounts
                ],
            ]
        ];
    }

    public function loadData(?array $filter = null){
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement(){
        return "document.getElementById('revenueSourceChart')";
    }

    protected function buildChartConfig(){
        /* Viết cấu hình biểu đồ tại đây */
        $revenueSourceData = json_encode($this->data);
        
        return <<<JS
        {
            chart: {
                type: 'line',
                height: 350,
                background: 'transparent',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            series: (JSON.parse('$revenueSourceData').series || []).map(series => ({
                name: series.name,
                data: series.data
            })),
            xaxis: {
                categories: (JSON.parse('$revenueSourceData').labels || []),
                labels: {
                    style: {
                        colors: '#ffffff',
                        fontSize: '12px',
                        fontWeight: 500
                    }
                },
                axisBorder: {
                    show: true,
                    color: '#374151'
                },
                axisTicks: {
                    show: true,
                    color: '#374151'
                },
                title: {
                    text: 'Thời gian',
                    style: {
                        color: '#9CA3AF',
                        fontSize: '14px',
                        fontWeight: 600
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#ffffff',
                        fontSize: '12px',
                        fontWeight: 500
                    },
                    formatter: function(value) {
                        return new Intl.NumberFormat('vi-VN', {
                            notation: 'compact',
                            maximumFractionDigits: 1
                        }).format(value);
                    }
                },
                title: {
                    text: 'Doanh thu (VNĐ)',
                    style: {
                        color: '#9CA3AF',
                        fontSize: '14px',
                        fontWeight: 600
                    }
                },
                axisBorder: {
                    show: true,
                    color: '#374151'
                }
            },
            colors: ['#3B82F6', '#F59E0B'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3,
                lineCap: 'round'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'vertical',
                    shadeIntensity: 0.3,
                    gradientToColors: ['#1E40AF', '#D97706'],
                    inverseColors: false,
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 100]
                }
            },
            markers: {
                size: 6,
                strokeWidth: 2,
                strokeColors: '#111827',
                fillColors: ['#3B82F6', '#F59E0B'],
                hover: {
                    size: 8
                }
            },
            grid: {
                borderColor: '#374151',
                strokeDashArray: 5,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center',
                labels: {
                    colors: '#ffffff',
                    fontSize: '13px',
                    fontWeight: 600
                },
                markers: {
                    width: 12,
                    height: 12,
                    radius: 6
                },
                itemMargin: {
                    horizontal: 20,
                    vertical: 8
                }
            },
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '14px',
                    fontFamily: 'Inter, system-ui, sans-serif'
                },
                custom: function({seriesIndex, dataPointIndex, w}) {
                    const series = w.config.series[seriesIndex];
                    const seriesName = series.name;
                    const value = series.data[dataPointIndex] || 0;
                    const date = w.config.xaxis.categories[dataPointIndex] || 'N/A';
                    
                    // Lấy dữ liệu gốc để tính toán
                    const originalData = JSON.parse('$revenueSourceData');
                    const currentIndex = dataPointIndex;
                    
                    // Lấy doanh thu vé và đồ ăn của ngày hiện tại
                    const ticketRevenue = originalData.series[0]?.data[currentIndex] || 0;
                    const foodRevenue = originalData.series[1]?.data[currentIndex] || 0;
                    const totalRevenue = ticketRevenue + foodRevenue;
                    
                    // Lấy số lượng đơn hàng
                    const ticketCounts = originalData.series[0]?.counts[currentIndex] || 0;
                    const foodCounts = originalData.series[1]?.counts[currentIndex] || 0;
                    
                    // Tính % thay đổi so với ngày trước (nếu có)
                    let ticketChangePercent = 0;
                    let foodChangePercent = 0;
                    
                    if (currentIndex > 0) {
                        const prevTicketRevenue = originalData.series[0]?.data[currentIndex - 1] || 0;
                        const prevFoodRevenue = originalData.series[1]?.data[currentIndex - 1] || 0;
                        
                        if (prevTicketRevenue > 0) {
                            ticketChangePercent = Math.round(((ticketRevenue - prevTicketRevenue) / prevTicketRevenue) * 100);
                        }
                        if (prevFoodRevenue > 0) {
                            foodChangePercent = Math.round(((foodRevenue - prevFoodRevenue) / prevFoodRevenue) * 100);
                        }
                    }
                    
                    // Xác định icon và màu sắc
                    let icon = '💰';
                    let bgColor = 'rgba(59, 130, 246, 0.1)';
                    let borderColor = 'rgba(59, 130, 246, 0.3)';
                    let textColor = '#93C5FD';
                    
                    if (seriesName.includes('đồ ăn')) {
                        icon = '🍿';
                        bgColor = 'rgba(245, 158, 11, 0.1)';
                        borderColor = 'rgba(245, 158, 11, 0.3)';
                        textColor = '#FCD34D';
                    }
                    
                    // Format tiền tệ
                    const formatCurrency = (amount) => {
                        return new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(amount);
                    };
                    
                    // Format ngày tháng
                    const formatDate = (dateStr) => {
                        if (dateStr === 'N/A' || !dateStr) return 'Không xác định';
                        
                        const today = new Date();
                        const currentYear = today.getFullYear();
                        
                        // Nếu dateStr có dạng "d/m", chuyển thành ngày đầy đủ
                        const parts = dateStr.split('/');
                        if (parts.length === 2) {
                            const day = parts[0].padStart(2, '0');
                            const month = parts[1].padStart(2, '0');
                            return day + '/' + month + '/' + currentYear;
                        }
                        
                        // Nếu đã có format đầy đủ thì trả về luôn
                        return dateStr;
                    };
                    
                    return '<div style="' +
                           'padding: 20px; ' +
                           'background: linear-gradient(135deg, rgba(17, 24, 39, 0.98), rgba(31, 41, 55, 0.95)); ' +
                           'border-radius: 16px; ' +
                           'min-width: 320px; ' +
                           'box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.05); ' +
                           'border: 1px solid rgba(75, 85, 99, 0.3); ' +
                           'backdrop-filter: blur(20px);' +
                           '">' +
                           
                           // Hàng 1: Doanh thu vé và đồ ăn - trải dàn ngang
                           '<div style="display: flex; gap: 12px; margin-bottom: 12px;">' +
                           
                           // Card doanh thu vé - mở rộng
                           '<div style="' +
                           'flex: 1; ' +
                           'background: rgba(59, 130, 246, 0.12); ' +
                           'padding: 16px; ' +
                           'border-radius: 12px; ' +
                           'border: 1px solid rgba(59, 130, 246, 0.4); ' +
                           'text-align: center; ' +
                           'transition: all 0.3s ease;' +
                           '">' +
                           '<div style="font-size: 20px; margin-bottom: 8px;">🎬</div>' +
                           '<div style="font-size: 11px; color: #93C5FD; font-weight: 700; margin-bottom: 6px; letter-spacing: 0.5px;">VÉ</div>' +
                           '<div style="font-size: 14px; font-weight: 800; color: #FFFFFF; margin-bottom: 4px;">' + formatCurrency(ticketRevenue) + '</div>' +
                           (ticketChangePercent !== 0 ? '<div style="font-size: 10px; color: ' + (ticketChangePercent > 0 ? '#10B981' : '#EF4444') + '; font-weight: 700;">' + 
                           (ticketChangePercent > 0 ? '+' : '') + ticketChangePercent + '%</div>' : '') +
                           '</div>' +
                           
                           // Card doanh thu đồ ăn - mở rộng
                           '<div style="' +
                           'flex: 1; ' +
                           'background: rgba(245, 158, 11, 0.12); ' +
                           'padding: 16px; ' +
                           'border-radius: 12px; ' +
                           'border: 1px solid rgba(245, 158, 11, 0.4); ' +
                           'text-align: center; ' +
                           'transition: all 0.3s ease;' +
                           '">' +
                           '<div style="font-size: 20px; margin-bottom: 8px;">🍿</div>' +
                           '<div style="font-size: 11px; color: #FCD34D; font-weight: 700; margin-bottom: 6px; letter-spacing: 0.5px;">ĐỒ ĂN</div>' +
                           '<div style="font-size: 14px; font-weight: 800; color: #FFFFFF; margin-bottom: 4px;">' + formatCurrency(foodRevenue) + '</div>' +
                           (foodChangePercent !== 0 ? '<div style="font-size: 10px; color: ' + (foodChangePercent > 0 ? '#10B981' : '#EF4444') + '; font-weight: 700;">' + 
                           (foodChangePercent > 0 ? '+' : '') + foodChangePercent + '%</div>' : '') +
                           '</div>' +
                           
                           '</div>' +
                           
                           // Hàng 2: Tổng doanh thu và thông tin đơn hàng - trải dàn ngang
                           '<div style="display: flex; gap: 12px;">' +
                           
                           // Card tổng doanh thu - mở rộng
                           '<div style="' +
                           'flex: 1; ' +
                           'background: rgba(16, 185, 129, 0.12); ' +
                           'padding: 16px; ' +
                           'border-radius: 12px; ' +
                           'border: 1px solid rgba(16, 185, 129, 0.4); ' +
                           'text-align: center; ' +
                           'transition: all 0.3s ease;' +
                           '">' +
                           '<div style="font-size: 20px; margin-bottom: 8px;">💵</div>' +
                           '<div style="font-size: 11px; color: #6EE7B7; font-weight: 700; margin-bottom: 6px; letter-spacing: 0.5px;">TỔNG</div>' +
                           '<div style="font-size: 14px; font-weight: 800; color: #FFFFFF;">' + formatCurrency(totalRevenue) + '</div>' +
                           '</div>' +
                           
                           // Card thông tin đơn hàng - mở rộng
                           '<div style="' +
                           'flex: 1; ' +
                           'background: rgba(55, 65, 81, 0.4); ' +
                           'padding: 16px; ' +
                           'border-radius: 12px; ' +
                           'border: 1px solid rgba(75, 85, 99, 0.3); ' +
                           'text-align: center; ' +
                           'transition: all 0.3s ease;' +
                           '">' +
                           '<div style="font-size: 20px; margin-bottom: 8px;">📊</div>' +
                           '<div style="font-size: 11px; color: #9CA3AF; font-weight: 700; margin-bottom: 6px; letter-spacing: 0.5px;">ĐƠN HÀNG</div>' +
                           '<div style="font-size: 11px; color: #FFFFFF; margin-bottom: 3px; font-weight: 600;">Vé: ' + ticketCounts.toLocaleString('vi-VN') + '</div>' +
                           '<div style="font-size: 11px; color: #FFFFFF; font-weight: 600;">Ăn: ' + foodCounts.toLocaleString('vi-VN') + '</div>' +
                           '</div>' +
                           
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
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left'
                    }
                }
            }, {
                breakpoint: 576,
                options: {
                    chart: {
                        height: 280
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left',
                        fontSize: '12px'
                    }
                }
            }]
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
        return "updateDataRevenueSourceChart";
    }

    public function compileJavascript(){
        $ctxText = "ctxRevenueSourceChart";
        $optionsText = "optionsRevenueSourceChart";
        $chartText = "chartRevenueSourceChart";
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
