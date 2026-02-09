<x-app-layout>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
        .animate-slide-in { animation: slideInRight 0.4s ease-out forwards; }
        .animate-scale-in { animation: scaleIn 0.5s ease-out forwards; }
        .chart-container { opacity: 0; animation: scaleIn 0.5s ease-out 0.2s forwards; }
    </style>

    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-slate-50">
        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">

                <!-- Header Section -->
                <div class="mb-8 animate-slide-in">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('dashboard') }}" 
                               class="group flex items-center justify-center w-12 h-12 rounded-xl bg-white border border-slate-200 shadow-sm hover:shadow-md hover:border-sky-300 transition-all duration-300">
                                <svg class="w-5 h-5 text-slate-600 group-hover:text-sky-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-3xl font-bold text-slate-800">Rekap Bulanan</h1>
                                <p class="text-slate-500 mt-1">Grafik pergerakan barang masuk & keluar per bulan</p>
                            </div>
                        </div>

                        <!-- Month Filter -->
                        <form method="GET" class="flex items-center gap-3">
                            <div class="relative">
                                <input type="month"
                                       name="month_year"
                                       value="{{ $selected }}"
                                       onchange="this.form.submit()"
                                       class="appearance-none bg-white rounded-xl border border-slate-200 text-sm px-4 py-3 pr-10 shadow-sm hover:border-sky-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition-all duration-300 cursor-pointer">
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="chart-container bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-sky-50 to-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800">Grafik Pergerakan Stok Harian</h3>
                                <p class="text-sm text-slate-500 mt-0.5">Data transaksi per hari dalam bulan {{ \Carbon\Carbon::createFromFormat('Y-m', $selected)->translatedFormat('F Y') }}</p>
                            </div>
                            <div class="flex items-center gap-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                    <span class="text-slate-600">Masuk</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                                    <span class="text-slate-600">Keluar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative h-80">
                            <canvas id="rekapChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('rekapChart').getContext('2d');
            
            // Gradient untuk Barang Masuk
            const gradientIn = ctx.createLinearGradient(0, 0, 0, 320);
            gradientIn.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
            gradientIn.addColorStop(1, 'rgba(16, 185, 129, 0.02)');
            
            // Gradient untuk Barang Keluar
            const gradientOut = ctx.createLinearGradient(0, 0, 0, 320);
            gradientOut.addColorStop(0, 'rgba(249, 115, 22, 0.25)');
            gradientOut.addColorStop(1, 'rgba(249, 115, 22, 0.02)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [
                        {
                            label: 'Barang Masuk',
                            data: @json($stockInData),
                            borderColor: '#10b981',
                            backgroundColor: gradientIn,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 7,
                            pointHoverBackgroundColor: '#10b981',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 3
                        },
                        {
                            label: 'Barang Keluar',
                            data: @json($stockOutData),
                            borderColor: '#f97316',
                            backgroundColor: gradientOut,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: '#f97316',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 7,
                            pointHoverBackgroundColor: '#f97316',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    },
                    interaction: {
                        intersect: true,
                        mode: 'nearest'
                    },
                    onHover: function(event, elements, chart) {
                        const canvas = chart.canvas;
                        if (elements.length > 0) {
                            canvas.style.cursor = 'pointer';
                        } else {
                            canvas.style.cursor = 'default';
                        }
                    },
                    onClick: function(event, elements, chart) {
                        if (elements.length > 0) {
                            const element = elements[0];
                            const datasetIndex = element.datasetIndex;
                            const datasetLabel = chart.data.datasets[datasetIndex].label;
                            
                            if (datasetLabel === 'Barang Masuk') {
                                window.location.href = '{{ route("stock-ins.index") }}';
                            } else if (datasetLabel === 'Barang Keluar') {
                                window.location.href = '{{ route("stock-outs.index") }}';
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#1e293b',
                            bodyColor: '#475569',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 14,
                            cornerRadius: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            displayColors: true,
                            callbacks: {
                                title: function(context) {
                                    return 'Tanggal ' + context[0].label;
                                },
                                label: function(context) {
                                    return ' ' + context.dataset.label + ': ' + context.parsed.y + ' unit';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { 
                                font: { size: 11, weight: '500' }, 
                                color: '#64748b',
                                maxRotation: 0,
                                minRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 15
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { 
                                color: '#f1f5f9', 
                                drawBorder: false 
                            },
                            ticks: { 
                                font: { size: 11 }, 
                                color: '#64748b',
                                stepSize: 1,
                                padding: 8
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
