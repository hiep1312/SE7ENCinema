<div class="container">
    <div class="chart-grid">
        <div class="chart-container">
            <div class="chart-header">📈 Doanh thu theo tháng</div>
            <div id="revenueChart"></div>
        </div>

        <div class="chart-container">
            <div class="chart-header">🎟️ Vé bán theo tháng</div>
            <div id="ticketChart"></div>
        </div>

        <div class="chart-container">
            <div class="chart-header">📊 Trạng thái đơn hàng</div>
            <div id="statusChart"></div>
        </div>

        <div class="chart-container">
            <div class="chart-header">🎬 Top 5 phim doanh thu cao</div>
            <div id="topMoviesChart"></div>
        </div>
    </div>
<style>
    .chart-container {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .chart-container:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    }

    .chart-header {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #2c3e50;
        font-family: 'Segoe UI', sans-serif;
        text-align: center;
    }

    .apexcharts-legend-text,
    .apexcharts-xaxis-label,
    .apexcharts-yaxis-label,
    .apexcharts-tooltip-text {
        font-family: 'Segoe UI', sans-serif !important;
        font-size: 13px !important;
        color: #444 !important;
    }

    .apexcharts-tooltip {
        border-radius: 8px !important;
        padding: 0.5rem !important;
    }

    .apexcharts-title-text {
        font-size: 15px !important;
        color: #34495e !important;
    }

    .chart-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 1.5rem;
    }
</style>
</div>

@script
<script>
    createScChart(document.querySelector('#revenueChart'), {
        chart: { type: 'area', height: 300 },
        series: [{ name: 'Doanh thu (VND)', data: @json(array_values($revenueData)) }],
        xaxis: { categories: @json(array_map(fn($m) => 'Tháng ' . $m, array_keys($revenueData))) },
        dataLabels: { enabled: true },
        stroke: { curve: 'smooth' },
    });

    createScChart(document.querySelector('#ticketChart'), {
        chart: { type: 'bar', height: 300 },
        series: [{ name: 'Vé đã bán', data: @json(array_values($ticketData)) }],
        xaxis: { categories: @json(array_map(fn($m) => 'Tháng ' . $m, array_keys($ticketData))) },
        plotOptions: {
            bar: {
                borderRadius: 6,
                horizontal: false,
                columnWidth: '45%',
            }
        },
        colors: ['#00b894']
    });

    createScChart(document.querySelector('#statusChart'), {
        chart: { type: 'donut', height: 320 },
        series: @json(array_values($statusData)),
        labels: @json(array_map(fn($v) => ucfirst($v), array_keys($statusData))),
    });

    createScChart(document.querySelector('#topMoviesChart'), {
        chart: { type: 'bar', height: 300 },
        series: [{
            name: 'Doanh thu',
            data: @json(array_values($topMovies))
        }],
        xaxis: {
            categories: @json(array_keys($topMovies)),
            labels: { rotate: -15 }
        },
        colors: ['#fdcb6e']
    });
</script>
@endscript
