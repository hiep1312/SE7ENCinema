<?php

namespace App\Charts\ChartRooms;

use App\Models\BookingSeat;

class RoomSeatStatusData
{
    protected $data;
    protected $room;

    public function __construct($room)
    {
        $this->room = $room;
    }

    protected function queryData(?string $filter = null)
    {
        $startDate = now()->subDays(2)->startOfDay();
        $endDate = now()->endOfDay();
        $seatsByType = $this->room->seats->groupBy('seat_type');
        $seatTypeStats = [];

        $typeNames = [
            'standard' => 'Ghế thường',
            'vip' => 'Ghế VIP',
            'couple' => 'Ghế đôi',
        ];

        $statusLabels = ['Còn trống', 'Đã đặt', 'Bảo trì'];

        $chartCategories = [];
        $chartSeries = [
            [
                'name' => $statusLabels[0],
                'data' => []
            ],
            [
                'name' => $statusLabels[1],
                'data' => []
            ],
            [
                'name' => $statusLabels[2],
                'data' => []
            ]
        ];

        foreach ($seatsByType as $type => $seats) {
            if ($seats->count() == 0) continue;

            $seatTypeName = $typeNames[$type] ?? ucfirst($type);

            $bookedCount = BookingSeat::join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
                ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
                ->join('seats', 'booking_seats.seat_id', '=', 'seats.id')
                ->where('showtimes.room_id', $this->room->id)
                ->where('seats.seat_type', $type)
                ->where('bookings.status', 'paid')
                ->whereBetween('bookings.created_at', [$startDate, $endDate])
                ->distinct('booking_seats.seat_id')
                ->count();

            $maintenanceCount = $seats->whereIn('status', ['maintenance', 'disabled'])->count();
            $totalByType = $seats->count();
            $availableCount = max(0, $totalByType - $bookedCount - $maintenanceCount);

            $bookedCount = max(0, $bookedCount);
            $maintenanceCount = max(0, $maintenanceCount);

            $chartCategories[] = $seatTypeName;
            $chartSeries[0]['data'][] = $availableCount;
            $chartSeries[1]['data'][] = $bookedCount;
            $chartSeries[2]['data'][] = $maintenanceCount;

            $seatTypeStats[] = [
                'name' => $seatTypeName,
                'type' => $type,
                'total' => $totalByType,
                'available' => $availableCount,
                'booked' => $bookedCount,
                'maintenance' => $maintenanceCount,
                'utilization_rate' => $totalByType > 0 ? round(($bookedCount / $totalByType) * 100, 1) : 0
            ];
        }

        if (empty($chartCategories)) {
            return [
                'total_seats' => 0,
                'booked_seats' => 0,
                'available_seats' => 0,
                'maintenance_seats' => 0,
                'occupancy_percentage' => 0,
                'seat_types' => [],
                'chart_data' => [
                    'categories' => [],
                    'series' => [
                        ['name' => $statusLabels[0], 'data' => []],
                        ['name' => $statusLabels[1], 'data' => []],
                        ['name' => $statusLabels[2], 'data' => []]
                    ]
                ]
            ];
        }

        $totalSeats = $this->room->seats->count();
        $totalBooked = array_sum($chartSeries[1]['data']);
        $totalMaintenance = array_sum($chartSeries[2]['data']);
        $totalAvailable = array_sum($chartSeries[0]['data']);

        return [
            'total_seats' => $totalSeats,
            'booked_seats' => $totalBooked,
            'available_seats' => $totalAvailable,
            'maintenance_seats' => $totalMaintenance,
            'occupancy_percentage' => $totalSeats > 0 ? round(($totalBooked / $totalSeats) * 100, 1) : 0,
            'seat_types' => $seatTypeStats,
            'chart_data' => [
                'categories' => $chartCategories,
                'series' => $chartSeries
            ]
        ];
    }

    public function loadData(?string $filter = null)
    {
        $this->data = $this->queryData($filter);
    }

    protected function bindDataToElement()
    {
        return "document.getElementById('seatStatusChart')";
    }

    protected function buildChartConfig()
    {
        $roomSeatStatusData = $this->data['chart_data'];
        $roomSeatStatusDataJS = json_encode($roomSeatStatusData);

        return <<<JS
        (function() {
            const seatStatusData = {$roomSeatStatusDataJS};

            if (!seatStatusData.categories || seatStatusData.categories.length === 0) {
                return {
                    chart: {
                        type: 'bar',
                        height: 420,
                        background: 'transparent'
                    },
                    series: [],
                    xaxis: {
                        categories: []
                    },
                    noData: {
                        text: 'Không có dữ liệu ghế',
                        align: 'center',
                        verticalAlign: 'middle',
                        style: {
                            color: '#ffffff',
                            fontSize: '16px'
                        }
                    }
                };
            }

            const seatTypeBaseColors = {
                'Ghế thường': '#28a745',
                'Ghế VIP': '#ffc107',
                'Ghế đôi': '#dc3545'
            };

            const createColorVariations = (baseColor, opacity) => {
                const hex = baseColor.replace('#', '');
                const r = parseInt(hex.substring(0, 2), 16);
                const g = parseInt(hex.substring(2, 4), 16);
                const b = parseInt(hex.substring(4, 6), 16);
                return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + opacity + ')';
            };

            const enhancedSeries = seatStatusData.series.map((serie, statusIndex) => {
                const opacities = [0.4, 0.8, 1.0];
                const currentOpacity = opacities[statusIndex];

                return {
                    name: serie.name,
                    data: serie.data.map((value, categoryIndex) => {
                        const seatType = seatStatusData.categories[categoryIndex];
                        const baseColor = seatTypeBaseColors[seatType] || '#6c757d';
                        const colorWithOpacity = createColorVariations(baseColor, currentOpacity);

                        return {
                            x: seatType,
                            y: value,
                            fillColor: colorWithOpacity
                        };
                    })
                };
            });

            return {
                chart: {
                    type: 'bar',
                    height: 420,
                    background: 'transparent',
                    toolbar: {
                        show: true
                    },
                    stacked: true,
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '70%',
                        endingShape: 'rounded',
                        borderRadius: 4,
                        dataLabels: {
                            total: {
                                enabled: true,
                                offsetX: 0,
                                offsetY: -5,
                                style: {
                                    fontSize: '12px',
                                    fontWeight: 600,
                                    color: '#ffffff'
                                },
                                formatter: function(val, opts) {
                                    const categoryIndex = opts.dataPointIndex;
                                    const total = seatStatusData.series.reduce((sum, s) =>
                                        sum + (s.data[categoryIndex] || 0), 0);
                                    return total > 0 ? total + ' ghế' : '';
                                }
                            }
                        }
                    }
                },
                series: enhancedSeries,
                xaxis: {
                    categories: seatStatusData.categories,
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '12px',
                            fontWeight: 600
                        },
                        rotate: seatStatusData.categories.length > 3 ? -45 : 0,
                        maxHeight: 100
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    title: {
                        text: 'Số lượng ghế',
                        style: {
                            color: '#ffffff',
                            fontSize: '14px'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '12px'
                        },
                        formatter: function(value) {
                            return Math.floor(value);
                        }
                    }
                },
                colors: ['rgba(255,255,255,0.4)', 'rgba(255,255,255,0.8)', 'rgba(255,255,255,1.0)'],
                fill: {
                    opacity: 1,
                    type: 'solid'
                },
                stroke: {
                    width: 1,
                    colors: ['transparent']
                },
                grid: {
                    show: true,
                    borderColor: '#2d3748',
                    strokeDashArray: 1,
                    xaxis: { lines: { show: false } },
                    yaxis: { lines: { show: true } }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    labels: {
                        colors: '#ffffff',
                        fontSize: '12px'
                    },
                    markers: {
                        width: 12,
                        height: 12,
                        radius: 2,
                        fillColors: ['rgba(255,255,255,0.4)', 'rgba(255,255,255,0.8)', 'rgba(255,255,255,1.0)']
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 5
                    }
                },
                tooltip: {
                    theme: 'dark',
                    shared: true,
                    intersect: false,
                    style: { fontSize: '12px' },
                    custom: function({series, seriesIndex, dataPointIndex, w}) {
                        if (!seatStatusData.categories || !seatStatusData.categories[dataPointIndex]) {
                            return '<div style="padding: 10px; background: rgba(0,0,0,0.9); border-radius: 6px;"><div style="color: #ccc; font-size: 11px;">Không có dữ liệu</div></div>';
                        }

                        const category = seatStatusData.categories[dataPointIndex];
                        const baseColor = seatTypeBaseColors[category] || '#6c757d';
                        const total = series.reduce((sum, s) => sum + (s[dataPointIndex] || 0), 0);

                        if (total === 0) {
                            return '<div style="padding: 10px; background: rgba(0,0,0,0.9); border-radius: 6px; border-left: 4px solid ' + baseColor + ';"><div style="font-weight: bold; color: ' + baseColor + '; margin-bottom: 8px;"><i class="fas fa-chair"></i> ' + category + '</div><div style="color: #ccc; font-size: 11px;">Không có dữ liệu ghế</div></div>';
                        }

                        let tooltipContent = '<div style="padding: 12px; background: rgba(0,0,0,0.95); border-radius: 8px; border-left: 4px solid ' + baseColor + '; min-width: 200px;"><div style="font-weight: bold; color: ' + baseColor + '; margin-bottom: 10px; font-size: 13px;"><i class="fas fa-chair"></i> ' + category + '</div><div style="font-size: 11px; color: #aaa; margin-bottom: 8px; border-bottom: 1px solid #333; padding-bottom: 4px;"><strong style="color: white;">Tổng: ' + total + ' ghế</strong></div>';

                        const statusOpacities = ['0.4', '0.8', '1.0'];
                        const statusIcons = ['○', '●', '■'];

                        series.forEach((seriesData, idx) => {
                            const value = seriesData[dataPointIndex] || 0;
                            if (value > 0) {
                                const seriesName = w.config.series[idx].name;
                                const percentage = Math.round((value / total) * 100);
                                const opacity = statusOpacities[idx];
                                const icon = statusIcons[idx];
                                const colorStyle = 'background: ' + baseColor + '; opacity: ' + opacity + ';';

                                tooltipContent += '<div style="margin: 4px 0; display: flex; justify-content: space-between; align-items: center;"><div style="display: flex; align-items: center;"><div style="width: 12px; height: 12px; ' + colorStyle + ' margin-right: 8px; border-radius: 2px;"></div><span style="color: #ddd; font-size: 11px;">' + icon + ' ' + seriesName + ':</span></div><strong style="color: white; font-size: 12px;">' + value + ' ghế (' + percentage + '%)</strong></div>';
                            }
                        });

                        tooltipContent += '<div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #333; font-size: 10px; color: #888;">💡 Màu cột thể hiện loại ghế, độ đậm thể hiện trạng thái</div></div>';
                        return tooltipContent;
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        colors: ['#ffffff'],
                        fontSize: '10px',
                        fontWeight: 'bold',
                        textStroke: '1px rgba(0,0,0,0.5)'
                    },
                    formatter: function(val) {
                        return val > 0 ? val : '';
                    },
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 1,
                        opacity: 0.8
                    }
                },
                responsive: [{
                    breakpoint: 768,
                    options: {
                        plotOptions: {
                            bar: { columnWidth: '90%' }
                        },
                        xaxis: {
                            labels: {
                                rotate: -45,
                                style: { fontSize: '10px' }
                            }
                        },
                        dataLabels: {
                            style: { fontSize: '9px' }
                        }
                    }
                }]
            };
        })()
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
        return "updateDataRoomSeatStatusData";
    }

    public function compileJavascript()
    {
        $ctxText = "ctxRoomSeatStatusData";
        $optionsText = "optionsRoomSeatStatusData";
        $chartText = "chartRoomSeatStatusData";

        echo <<<JS
        Livewire.on("{$this->getEventName()}", async function ([data]){
            await new Promise(resolve => setTimeout(resolve, 100));
            const {$ctxText} = {$this->bindDataToElement()};
            if({$ctxText}){
                try {
                    if(window.{$chartText} && typeof window.{$chartText}.destroy === 'function') {
                        window.{$chartText}.destroy();
                    }
                    window.{$optionsText} = data && typeof data === 'string' ? new Function("return " + data)() : {$this->buildChartConfig()};
                    window.{$chartText} = createScChart({$ctxText}, window.{$optionsText});
                } catch(error) {
                    console.error('Error creating chart:', error);
                }
            }
        });
        JS;
    }
}
