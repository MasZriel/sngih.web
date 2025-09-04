@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<!-- Stat Cards -->
<div class="row g-4">
    <!-- Total Revenue -->
    <div class="col-lg-3 col-md-6 stat-card-col">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary mb-1">Total Revenue</p>
                        <h4 class="mb-2 fw-bold">Rp {{ number_format($revenueStats['count'], 0, ',', '.') }}</h4>
                        <p class="badge bg-{{ $revenueStats['percentage'] >= 0 ? 'success' : 'danger' }} bg-opacity-10 text-{{ $revenueStats['percentage'] >= 0 ? 'success' : 'danger' }} mb-0">
                            {{ $revenueStats['percentage'] >= 0 ? '+' : '' }}{{ $revenueStats['percentage'] }}%
                        </p>
                    </div>
                    <div class="text-end">
                        <div style="width: 100px; height: 50px;">
                            <canvas id="revenueSparkline"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="col-lg-3 col-md-6 stat-card-col">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary mb-1">Total Orders</p>
                        <h4 class="mb-2 fw-bold">{{ $orderStats['count'] }}</h4>
                        <p class="badge bg-{{ $orderStats['percentage'] >= 0 ? 'success' : 'danger' }} bg-opacity-10 text-{{ $orderStats['percentage'] >= 0 ? 'success' : 'danger' }} mb-0">
                            {{ $orderStats['percentage'] >= 0 ? '+' : '' }}{{ $orderStats['percentage'] }}%
                        </p>
                    </div>
                    <div class="text-end">
                        <div style="width: 100px; height: 50px;">
                            <canvas id="ordersSparkline"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Sold -->
    <div class="col-lg-3 col-md-6 stat-card-col">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary mb-1">Products Sold</p>
                        <h4 class="mb-2 fw-bold">{{ $productSalesStats['count'] }}</h4>
                        <p class="badge bg-{{ $productSalesStats['percentage'] >= 0 ? 'success' : 'danger' }} bg-opacity-10 text-{{ $productSalesStats['percentage'] >= 0 ? 'success' : 'danger' }} mb-0">
                            {{ $productSalesStats['percentage'] >= 0 ? '+' : '' }}{{ $productSalesStats['percentage'] }}%
                        </p>
                    </div>
                    <div class="text-end">
                        <div style="width: 100px; height: 50px;">
                            <canvas id="salesSparkline"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Customers -->
    <div class="col-lg-3 col-md-6 stat-card-col">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary mb-1">New Customers</p>
                        <h4 class="mb-2 fw-bold">{{ $customerStats['count'] }}</h4>
                        <p class="badge bg-{{ $customerStats['percentage'] >= 0 ? 'success' : 'danger' }} bg-opacity-10 text-{{ $customerStats['percentage'] >= 0 ? 'success' : 'danger' }} mb-0">
                            {{ $customerStats['percentage'] >= 0 ? '+' : '' }}{{ $customerStats['percentage'] }}%
                        </p>
                    </div>
                    <div class="text-end">
                        <div style="width: 100px; height: 50px;">
                            <canvas id="customersSparkline"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Chart & Lists -->
<div class="row g-4 mt-4">
    <!-- Statistics Chart -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title fw-bold">Statistics</h5>
                <div class="flex-grow-1" style="height: 350px;">
                    <canvas id="mainBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title fw-bold">Top Selling Product</h5>
                <div class="mt-3 flex-grow-1">
                    @forelse($topSellingProducts as $product)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('storage/' . $product->image) }}" class="rounded-3 me-3" style="width: 48px; height: 48px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <a href="#" class="text-decoration-none fw-semibold text-primary d-block">{{ $product->name }}</a>
                                <span class="text-secondary fs-sm">{{ $product->total_sold }} sold</span>
                            </div>
                            <h6 class="fw-bold text-end">Rp{{ number_format($product->price, 0, ',', '.') }}</h6>
                        </div>
                    @empty
                        <div class="d-flex h-100 justify-content-center align-items-center">
                            <p class="text-center text-secondary">No products sold yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = () => localStorage.getItem('theme') === 'dark';
    const textColor = () => isDark() ? '#E2E8F0' : '#718096';
    const gridColor = () => isDark() ? 'rgba(255, 255, 255, 0.1)' : '#EDF2F7';

    // Main Bar Chart
    const mainChartCtx = document.getElementById('mainBarChart').getContext('2d');
    const mainBarChart = new Chart(mainChartCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Total Orders',
                data: {!! json_encode($chartData) !!},
                backgroundColor: '#4A5568',
                borderRadius: 4,
                barThickness: 20,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor() }, ticks: { color: textColor() } },
                x: { grid: { display: false }, ticks: { color: textColor() } }
            }
        }
    });

    // Sparklines
    const sparklineOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { enabled: false } },
        elements: { point: { radius: 0 } },
        scales: { x: { display: false }, y: { display: false } }
    };

    const createSparkline = (id, data, color) => {
        const ctx = document.getElementById(id).getContext('2d');
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array.from(Array(data.length).keys()),
                datasets: [{
                    data: data,
                    borderColor: color,
                    borderWidth: 2,
                    tension: 0.4,
                }]
            },
            options: sparklineOptions
        });
    };

    const sparklines = [
        createSparkline('revenueSparkline', {!! json_encode($revenueStats['sparkline']) !!}, '#3B82F6'),
        createSparkline('ordersSparkline', {!! json_encode($orderStats['sparkline']) !!}, '#8B5CF6'),
        createSparkline('salesSparkline', {!! json_encode($productSalesStats['sparkline']) !!}, '#F59E0B'),
        createSparkline('customersSparkline', {!! json_encode($customerStats['sparkline']) !!}, '#EF4444')
    ];

    // Update charts on theme change
    window.addEventListener('themeChanged', (e) => {
        mainBarChart.options.scales.y.grid.color = gridColor();
        mainBarChart.options.scales.y.ticks.color = textColor();
        mainBarChart.options.scales.x.ticks.color = textColor();
        mainBarChart.data.datasets[0].backgroundColor = isDark() ? '#A0AEC0' : '#4A5568';
        mainBarChart.update();
    });
});
</script>
@endpush
