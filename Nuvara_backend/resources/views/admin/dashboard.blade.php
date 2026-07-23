@extends('layouts.admin')

@section('title', 'Admin - Dashboard')
@section('header_title', 'Dashboard Overview')

@section('content')
<div class="space-y-8 animate-fade-in text-left">

    <!-- Welcome Banner Card -->
    <div class="glass-card rounded-2xl p-6 md:p-8 shadow-lg relative overflow-hidden">
        <div class="absolute w-64 h-64 -top-20 -right-20 bg-brass/10 rounded-full blur-[80px] pointer-events-none"></div>
        <h2 class="text-2xl font-serif text-brass uppercase tracking-wide">
            Welcome back, Administrator
        </h2>
        <p class="text-sm text-gray-400 mt-2 leading-relaxed max-w-2xl">
            Secure admin access portal. Track dynamic sales statistics, inventory levels, category catalogs, and customer order checkout queues in real-time.
        </p>
    </div>

    <!-- Metrics Stats Grid (5 Cards Row) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Total Revenue -->
        <div class="glass-card rounded-xl p-6 shadow-md flex flex-col justify-between relative overflow-hidden transition-all duration-300 hover:border-brass/30">
            <div class="flex justify-between items-start">
                <span class="text-[9px] text-gray-500 font-bold uppercase tracking-wider">Total Revenue</span>
                <span class="text-sm bg-brass/10 border border-brass/20 w-8 h-8 rounded-lg flex items-center justify-center select-none">💵</span>
            </div>
            <div class="mt-4">
                <h3 class="text-xl font-serif text-[#F5EFE4] font-bold">${{ number_format($revenueTotal, 2) }}</h3>
                <p class="text-[9px] text-green-400 font-bold mt-1">▲ Live earnings</p>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="glass-card rounded-xl p-6 shadow-md flex flex-col justify-between relative overflow-hidden transition-all duration-300 hover:border-indigo-500/30">
            <div class="flex justify-between items-start">
                <span class="text-[9px] text-gray-500 font-bold uppercase tracking-wider">Total Orders</span>
                <span class="text-sm bg-indigo-500/10 border border-indigo-500/20 w-8 h-8 rounded-lg flex items-center justify-center select-none">🛍️</span>
            </div>
            <div class="mt-4">
                <h3 class="text-xl font-serif text-[#F5EFE4] font-bold">{{ $ordersCount }}</h3>
                <p class="text-[9px] text-indigo-400 font-bold mt-1">▲ Checkout queue</p>
            </div>
        </div>

        <!-- Catalog Products -->
        <div class="glass-card rounded-xl p-6 shadow-md flex flex-col justify-between relative overflow-hidden transition-all duration-300 hover:border-emerald-500/30">
            <div class="flex justify-between items-start">
                <span class="text-[9px] text-gray-500 font-bold uppercase tracking-wider">Catalog Products</span>
                <span class="text-sm bg-emerald-500/10 border border-emerald-500/20 w-8 h-8 rounded-lg flex items-center justify-center select-none">📦</span>
            </div>
            <div class="mt-4">
                <h3 class="text-xl font-serif text-[#F5EFE4] font-bold">{{ $productsCount }}</h3>
                <p class="text-[9px] text-emerald-400 font-bold mt-1">● Active inventory</p>
            </div>
        </div>

        <!-- Active Categories -->
        <div class="glass-card rounded-xl p-6 shadow-md flex flex-col justify-between relative overflow-hidden transition-all duration-300 hover:border-amber-500/30">
            <div class="flex justify-between items-start">
                <span class="text-[9px] text-gray-500 font-bold uppercase tracking-wider">Active Categories</span>
                <span class="text-sm bg-amber-500/10 border border-amber-500/20 w-8 h-8 rounded-lg flex items-center justify-center select-none">🏷️</span>
            </div>
            <div class="mt-4">
                <h3 class="text-xl font-serif text-[#F5EFE4] font-bold">{{ $categoriesCount }}</h3>
                <p class="text-[9px] text-amber-400 font-bold mt-1">● Group taxonomies</p>
            </div>
        </div>

        <!-- Total Users -->
        <div class="glass-card rounded-xl p-6 shadow-md flex flex-col justify-between relative overflow-hidden transition-all duration-300 hover:border-blue-500/30">
            <div class="flex justify-between items-start">
                <span class="text-[9px] text-gray-500 font-bold uppercase tracking-wider">Total Users</span>
                <span class="text-sm bg-blue-500/10 border border-blue-500/20 w-8 h-8 rounded-lg flex items-center justify-center select-none">👥</span>
            </div>
            <div class="mt-4">
                <h3 class="text-xl font-serif text-[#F5EFE4] font-bold">{{ $usersCount }}</h3>
                <p class="text-[9px] text-blue-400 font-bold mt-1">● User directory</p>
            </div>
        </div>
    </div>

    <!-- Revenue Analytics Chart Card -->
    <div class="glass-card rounded-xl p-6 shadow-md">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-border pb-4 mb-6 gap-4">
            <div>
                <h4 class="text-xs font-bold text-brass uppercase tracking-wider">
                    Revenue Earnings Analytics
                </h4>
                <p class="text-[10px] text-gray-500 font-semibold mt-0.5">Live aggregated checkout metrics</p>
            </div>

            <!-- Chart Toggle buttons -->
            <div class="inline-flex rounded-lg bg-black/40 border border-border p-1">
                <button
                    onclick="switchChartPeriod('day')"
                    id="btn-period-day"
                    class="px-3.5 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wide transition-all bg-brass text-black">
                    Daily
                </button>
                <button
                    onclick="switchChartPeriod('month')"
                    id="btn-period-month"
                    class="px-3.5 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wide transition-all text-gray-400 hover:text-white">
                    Monthly
                </button>
                <button
                    onclick="switchChartPeriod('year')"
                    id="btn-period-year"
                    class="px-3.5 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wide transition-all text-gray-400 hover:text-white">
                    Yearly
                </button>
            </div>
        </div>

        <!-- Canvas -->
        <div class="relative w-full h-80">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Injected datasets from Laravel controller context
    const datasetDay = {
        labels: {!! json_encode($dayLabels) !!},
        values: {!! json_encode($dayValues) !!}
    };
    const datasetMonth = {
        labels: {!! json_encode($monthLabels) !!},
        values: {!! json_encode($monthValues) !!}
    };
    const datasetYear = {
        labels: {!! json_encode($yearLabels) !!},
        values: {!! json_encode($yearValues) !!}
    };

    let activeChart = null;

    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');

        // Custom gradient for premium line fill
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(197, 168, 128, 0.35)');
        gradient.addColorStop(1, 'rgba(197, 168, 128, 0.00)');

        activeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: datasetDay.labels,
                datasets: [{
                    label: 'Revenue ($)',
                    data: datasetDay.values,
                    borderColor: '#C5A880',
                    borderWidth: 2.5,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#C5A880',
                    pointBorderColor: '#0b0b0b',
                    pointBorderWidth: 1.5,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: '#1a1a1a'
                        },
                        ticks: {
                            color: '#7a7a7a',
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 10
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: '#1a1a1a'
                        },
                        ticks: {
                            color: '#7a7a7a',
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    });

    function switchChartPeriod(period) {
        if (!activeChart) return;

        // Reset button active styles
        const btns = ['day', 'month', 'year'];
        btns.forEach(b => {
            const el = document.getElementById('btn-period-' + b);
            if (b === period) {
                el.className = "px-3.5 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wide transition-all bg-brass text-black";
            } else {
                el.className = "px-3.5 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wide transition-all text-gray-400 hover:text-white";
            }
        });

        // Set datasets
        let targetDataset = datasetDay;
        if (period === 'month') {
            targetDataset = datasetMonth;
        } else if (period === 'year') {
            targetDataset = datasetYear;
        }

        // Apply to chart
        activeChart.data.labels = targetDataset.labels;
        activeChart.data.datasets[0].data = targetDataset.values;
        activeChart.update();
    }
</script>
@endsection