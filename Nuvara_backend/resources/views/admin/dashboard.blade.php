@extends('layouts.admin')

@section('title', 'Admin - Dashboard')
@section('header_title', 'Dashboard Overview')

@section('content')
<style>
.dash-shell{max-width:1440px;margin:auto}.dash-kicker{font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:#c5a880;font-weight:800}.dash-metrics>div{min-height:148px!important}.dash-metrics>div:nth-child(1){background:linear-gradient(135deg,#285c48,#173d31)!important}.dash-metrics>div:nth-child(2){background:linear-gradient(135deg,#293c68,#1c2947)!important}.dash-metrics>div:nth-child(3){background:linear-gradient(135deg,#276052,#164437)!important}.dash-metrics>div:nth-child(4){background:linear-gradient(135deg,#77552c,#493619)!important}.dash-chart{background:rgba(5,24,18,.5)!important}.light .dash-chart{background:#fff!important}.light .dash-metrics>div{color:#173d31!important}
</style>
<style>.dash-metrics>div{color:#fff!important}.dash-metrics>div span,.dash-metrics>div h3,.dash-metrics>div p{color:#fff!important}.dash-metrics>div h3{font-size:28px!important;font-weight:800!important;letter-spacing:-.03em}.dash-metrics>div p{opacity:.9}</style>
<style>.dash-metrics>div{background:linear-gradient(145deg,#2749a9,#2362e8)!important;border:0!important;border-radius:14px!important;color:#fff!important;min-height:154px!important;padding:22px!important;box-shadow:0 12px 24px rgba(35,85,200,.22)!important}.dash-metrics>div *,.light .dash-metrics>div *{color:#fff!important}.dash-metrics>div span{opacity:.86}.dash-metrics>div h3{font-size:28px!important;line-height:1.1!important;margin-top:16px!important}.dash-metrics>div p{font-size:11px!important;margin-top:10px!important}.dash-metrics>div p::first-letter{color:#6dffaf}</style>
<div class="dash-shell space-y-8 animate-fade-in text-left">

    <!-- Welcome Banner Card -->
    <div class="glass-card dashboard-hero rounded-2xl p-6 md:p-8 shadow-lg relative overflow-hidden">
        <div class="absolute w-64 h-64 -top-20 -right-20 bg-brass/10 rounded-full blur-[80px] pointer-events-none"></div>
        <h2 class="text-2xl font-serif text-brass uppercase tracking-wide">
            Welcome back, Administrator
        </h2>
        <p class="text-sm text-gray-400 mt-2 leading-relaxed max-w-2xl">
            Secure admin access portal. Track dynamic sales statistics, inventory levels, category catalogs, and customer order checkout queues in real-time.
        </p>
    </div>

    <!-- Metrics Stats Grid (5 Cards Row) -->
    <div class="dash-metrics grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
        <!-- Total Revenue -->
        <div class="glass-card dashboard-metric rounded-xl p-6 shadow-md flex flex-col justify-between relative overflow-hidden transition-all duration-300 hover:border-brass/30">
            <div class="flex justify-between items-start">
                <span class="text-[9px] text-gray-500 font-bold uppercase tracking-wider">Total Revenue</span>
                <span class="text-sm bg-brass/10 border border-brass/20 w-8 h-8 rounded-lg flex items-center justify-center select-none">💵</span>
            </div>
            <div class="mt-4">
                <h3 class="text-xl font-serif text-[#F5EFE4] font-bold">৳{{ number_format($revenueTotal, 2) }}</h3>
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
    <div class="dash-chart glass-card rounded-xl p-6 shadow-md">
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
                    label: 'Revenue (৳)',
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
<style>
.dash-chart{background:linear-gradient(145deg,#fffdf9,#f2ece2)!important;border:1px solid #d9cfbf!important}.dash-chart .relative{background:linear-gradient(180deg,rgba(197,168,128,.10),rgba(197,168,128,.02));border-radius:14px;padding:18px 14px 10px;box-shadow:inset 0 0 0 1px rgba(197,168,128,.12)}.dark .dash-chart{background:linear-gradient(145deg,#26363a,#1d2a2e)!important;border-color:#46565a!important}.dark .dash-chart .relative{background:linear-gradient(180deg,rgba(197,168,128,.16),rgba(19,36,35,.45))}.dash-chart canvas{filter:drop-shadow(0 8px 12px rgba(82,64,38,.12))}
</style>
@endsection
