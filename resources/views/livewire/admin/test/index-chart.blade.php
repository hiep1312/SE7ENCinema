<div class="container">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div id="revenueChart"></div>
        </div>
        <div class="col-md-6 mb-4">
            <div id="ticketChart"></div>
        </div>
        <div class="col-md-6 mb-4">
            <div id="statusChart"></div>
        </div>
        <div class="col-md-6 mb-4">
            <div id="topMoviesChart"></div>
        </div>
    </div>
    <style>
        #revenueChart, #ticketChart, #statusChart, #topMoviesChart {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            max-width: 100%;
        }

        .apexcharts-title-text,
        .apexcharts-legend-text,
        .apexcharts-xaxis-label,
        .apexcharts-yaxis-label {
            font-family: 'Segoe UI', sans-serif;
            font-size: 14px;
            color: #333;
        }

        .apexcharts-tooltip {
            border-radius: 8px;
            font-size: 13px;
            font-family: 'Segoe UI', sans-serif;
        }
    </style>
</div>

@script
<script>
    // Revenue line chart
    createScChart(document.querySelector('#revenueChart'), {
        chart: { type: 'line', height: 300 },
        series: [{
            name: 'Doanh thu (VND)',
            data: @json(array_values($revenueData))
        }],
        xaxis: {
            categories: @json(array_map(fn($m) => 'Tháng ' . $m, array_keys($revenueData)))
        },
        title: { text: 'Doanh thu theo tháng', align: 'center' }
    });

    // Ticket bar chart
    createScChart(document.querySelector('#ticketChart'), {
        chart: { type: 'bar', height: 300 },
        series: [{
            name: 'Vé đã bán',
            data: @json(array_values($ticketData))
        }],
        xaxis: {
            categories: @json(array_map(fn($m) => 'Tháng ' . $m, array_keys($ticketData)))
        },
        title: { text: 'Lượng vé theo tháng', align: 'center' }
    });

    // Booking status pie chart
    createScChart(document.querySelector('#statusChart'), {
        chart: { type: 'pie', height: 300 },
        series: @json(array_values($statusData)),
        labels: @json(array_keys($statusData)),
        title: { text: 'Tình trạng đơn hàng', align: 'center' }
    });

    // Top movies revenue chart
    createScChart(document.querySelector('#topMoviesChart'), {
        chart: { type: 'bar', height: 300 },
        series: [{
            name: 'Doanh thu',
            data: @json(array_values($topMovies))
        }],
        xaxis: {
            categories: @json(array_keys($topMovies))
        },
        title: { text: 'Top 5 phim doanh thu cao nhất', align: 'center' }
    });
</script>
@endscript
