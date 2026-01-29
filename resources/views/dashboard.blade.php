<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-slate-50">
        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">

                <!-- Header Section -->
                <div class="mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-800">Dashboard</h1>
                            <p class="text-slate-500 mt-1">
                                Selamat datang, 
                                <span class="font-semibold text-sky-600">{{ auth()->user()->username ?? auth()->user()->name }}</span>
                            </p>
                        </div>
                        
                        <!-- Live Clock -->
                        <div class="flex items-center gap-4">
                            <div class="bg-white rounded-2xl px-5 py-3 shadow-sm border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-sky-400 to-sky-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-slate-800 tabular-nums" id="liveClock">--:--:--</p>
                                        <p class="text-xs text-slate-500" id="liveDate">Loading...</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Live Status -->
                            <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 rounded-full border border-emerald-200">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                                <span class="text-sm font-medium text-emerald-700">Live</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    
                    <!-- Total Barang Card -->
                    <a href="{{ route('items.index') }}"
                       class="group relative bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-lg hover:border-sky-200 transition-all duration-300 overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-sky-100 to-sky-50 rounded-full -translate-y-16 translate-x-16 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500 mb-2">Total Barang</p>
                                <p class="text-4xl font-bold text-slate-800 mb-1" id="statTotalItems">{{ $totalItems ?? 0 }}</p>
                                <p class="text-xs text-slate-400 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                    Lihat detail
                                </p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center shadow-lg shadow-sky-500/30">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <!-- Stok Menipis Card -->
                    <a href="{{ route('items.index', ['filter' => 'low-stock']) }}"
                       class="group relative bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-lg hover:border-amber-200 transition-all duration-300 overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-100 to-amber-50 rounded-full -translate-y-16 translate-x-16 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500 mb-2">Stok Menipis</p>
                                <p class="text-4xl font-bold text-amber-500 mb-1" id="statLowStock">{{ $lowStock ?? 0 }}</p>
                                <p class="text-xs text-slate-400 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/>
                                    </svg>
                                    Perlu restok
                                </p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <!-- Stok Habis Card -->
                    <a href="{{ route('items.index', ['filter' => 'out-of-stock']) }}"
                       class="group relative bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-lg hover:border-rose-200 transition-all duration-300 overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-rose-100 to-rose-50 rounded-full -translate-y-16 translate-x-16 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500 mb-2">Stok Habis</p>
                                <p class="text-4xl font-bold text-rose-500 mb-1" id="statOutOfStock">{{ $outOfStock ?? 0 }}</p>
                                <p class="text-xs text-slate-400 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Segera pesan
                                </p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center shadow-lg shadow-rose-500/30">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    
                    <!-- Stock Movement Chart -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-sky-50 to-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800">Pergerakan Stok</h3>
                                    <p class="text-sm text-slate-500 mt-0.5">Berdasarkan tanggal transaksi</p>
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
                            <div class="relative h-72">
                                <canvas id="stockMovementChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Distribution Chart -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-sky-50 to-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800">Distribusi Stok</h3>
                                    <p class="text-sm text-slate-500 mt-0.5">Berdasarkan kategori</p>
                                </div>
                                <button onclick="refreshData()" class="p-2 rounded-xl hover:bg-sky-100 text-sky-600 transition-colors" title="Refresh Data">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="relative h-72">
                                <canvas id="stockDistributionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- Recent Stock In -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-800">Barang Masuk Terbaru</h3>
                                </div>
                                <a href="{{ route('stock-ins.index') }}" class="text-sm text-sky-600 hover:text-sky-700 font-medium flex items-center gap-1">
                                    Lihat Semua
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="space-y-3" id="recentStockInContainer">
                                @forelse($recentStockIn ?? [] as $item)
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-emerald-50 transition-colors group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800">{{ $item['item_name'] }}</p>
                                            <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($item['date'])->format('d M Y') }} - {{ $item['time'] }}</p>
                                        </div>
                                    </div>
                                    <span class="text-lg font-bold text-emerald-600 bg-emerald-100 px-3 py-1 rounded-lg">+{{ $item['quantity'] }}</span>
                                </div>
                                @empty
                                <div class="text-center py-12">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                    </div>
                                    <p class="text-slate-500">Belum ada data barang masuk</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Recent Stock Out -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-orange-50 to-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-800">Barang Keluar Terbaru</h3>
                                </div>
                                <a href="{{ route('stock-outs.index') }}" class="text-sm text-sky-600 hover:text-sky-700 font-medium flex items-center gap-1">
                                    Lihat Semua
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="space-y-3" id="recentStockOutContainer">
                                @forelse($recentStockOut ?? [] as $item)
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-orange-50 transition-colors group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800">{{ $item['item_name'] }}</p>
                                            <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($item['date'])->format('d M Y') }} - {{ $item['time'] }}</p>
                                        </div>
                                    </div>
                                    <span class="text-lg font-bold text-orange-600 bg-orange-100 px-3 py-1 rounded-lg">-{{ $item['quantity'] }}</span>
                                </div>
                                @empty
                                <div class="text-center py-12">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                    </div>
                                    <p class="text-slate-500">Belum ada data barang keluar</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Chart instances
        let stockMovementChart = null;
        let stockDistributionChart = null;
        
        // Initial data from Laravel - menggunakan tanggal dari data stock in/out
        const initialData = {
            chartLabels: @json($chartLabels ?? []),
            stockInData: @json($stockInData ?? []),
            stockOutData: @json($stockOutData ?? []),
            categoryLabels: @json($categoryLabels ?? []),
            categoryData: @json($categoryData ?? []),
            recentStockIn: @json($recentStockIn ?? []),
            recentStockOut: @json($recentStockOut ?? [])
        };

        // Chart colors dengan tema sky blue
        const categoryColors = [
            '#0ea5e9', '#10b981', '#f59e0b', '#ef4444', 
            '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'
        ];

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            startLiveClock();
            
            // Auto refresh data setiap 30 detik
            setInterval(refreshData, 30000);
        });

        // Live Clock - Update setiap detik
        function startLiveClock() {
            function updateClock() {
                const now = new Date();
                
                // Format waktu HH:MM:SS
                const timeStr = now.toLocaleTimeString('id-ID', { 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    second: '2-digit',
                    hour12: false 
                });
                
                // Format tanggal
                const dateStr = now.toLocaleDateString('id-ID', { 
                    weekday: 'long', 
                    day: 'numeric', 
                    month: 'long', 
                    year: 'numeric' 
                });
                
                document.getElementById('liveClock').textContent = timeStr;
                document.getElementById('liveDate').textContent = dateStr;
            }
            
            // Update immediately then every second
            updateClock();
            setInterval(updateClock, 1000);
        }

        // Initialize charts with Laravel data
        function initializeCharts() {
            // Stock Movement Line Chart
            const stockCtx = document.getElementById('stockMovementChart');
            if (stockCtx) {
                stockMovementChart = new Chart(stockCtx, {
                    type: 'line',
                    data: {
                        labels: initialData.chartLabels,
                        datasets: [
                            {
                                label: 'Barang Masuk',
                                data: initialData.stockInData,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.15)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.2,
                                pointBackgroundColor: '#10b981',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 8
                            },
                            {
                                label: 'Barang Keluar',
                                data: initialData.stockOutData,
                                borderColor: '#f97316',
                                backgroundColor: 'rgba(249, 115, 22, 0.15)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.2,
                                pointBackgroundColor: '#f97316',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 8
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 750, easing: 'easeInOutQuart' },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleFont: { size: 13, weight: 'bold' },
                                bodyFont: { size: 12 },
                                padding: 12,
                                cornerRadius: 10,
                                displayColors: true
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { 
                                    font: { size: 11 }, 
                                    color: '#64748b',
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f1f5f9', drawBorder: false },
                                ticks: { 
                                    font: { size: 11 }, 
                                    color: '#64748b',
                                    stepSize: 1
                                }
                            }
                        },
                        interaction: { intersect: false, mode: 'index' }
                    },
                    plugins: [{
                        id: 'chartClickHandler',
                        afterEvent: function(chart, event) {
                            if (event.event.type === 'click') {
                                const canvasPosition = Chart.helpers.getRelativePosition(event.event, chart);
                                const dataX = chart.scales.x.getValueForPixel(canvasPosition.x);
                                const dataY = chart.scales.y.getValueForPixel(canvasPosition.y);
                                
                                // Get the elements at this position
                                const elements = chart.getElementsAtEventForMode(
                                    event.event,
                                    'nearest',
                                    { intersect: true },
                                    true
                                );
                                
                                if (elements.length > 0) {
                                    const element = elements[0];
                                    const datasetLabel = chart.data.datasets[element.datasetIndex].label;
                                    
                                    if (datasetLabel === 'Barang Masuk') {
                                        window.location.href = '{{ route("stock-ins.index") }}';
                                    } else if (datasetLabel === 'Barang Keluar') {
                                        window.location.href = '{{ route("stock-outs.index") }}';
                                    }
                                }
                            }
                        }
                    }]
                });
            }

            // Stock Distribution Doughnut Chart
            const pieCtx = document.getElementById('stockDistributionChart');
            if (pieCtx) {
                stockDistributionChart = new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: initialData.categoryLabels,
                        datasets: [{
                            data: initialData.categoryData,
                            backgroundColor: categoryColors.slice(0, initialData.categoryLabels.length),
                            borderColor: '#ffffff',
                            borderWidth: 4,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        animation: { animateRotate: true, animateScale: true, duration: 750 },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: { size: 12 },
                                    color: '#475569'
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleFont: { size: 13, weight: 'bold' },
                                bodyFont: { size: 12 },
                                padding: 12,
                                cornerRadius: 10,
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                                        return context.label + ': ' + context.parsed + ' unit (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Refresh data via AJAX
        function refreshData() {
            fetch('{{ route("dashboard") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update stats with animation
                animateValue('statTotalItems', data.totalItems);
                animateValue('statLowStock', data.lowStock);
                animateValue('statOutOfStock', data.outOfStock);

                // Update Stock Movement Chart
                if (stockMovementChart) {
                    stockMovementChart.data.labels = data.chartLabels;
                    stockMovementChart.data.datasets[0].data = data.stockInData;
                    stockMovementChart.data.datasets[1].data = data.stockOutData;
                    stockMovementChart.update('active');
                }

                // Update Distribution Chart
                if (stockDistributionChart) {
                    stockDistributionChart.data.labels = data.categoryLabels;
                    stockDistributionChart.data.datasets[0].data = data.categoryData;
                    stockDistributionChart.data.datasets[0].backgroundColor = categoryColors.slice(0, data.categoryLabels.length);
                    stockDistributionChart.update('active');
                }

                // Update Recent Stock In
                updateRecentStockIn(data.recentStockIn);
                
                // Update Recent Stock Out
                updateRecentStockOut(data.recentStockOut);
            })
            .catch(error => {
                console.error('Error fetching dashboard data:', error);
            });
        }

        // Animate stat value change
        function animateValue(elementId, newValue) {
            const element = document.getElementById(elementId);
            if (!element) return;
            
            const currentValue = parseInt(element.textContent) || 0;
            
            if (currentValue !== newValue) {
                element.style.transform = 'scale(1.15)';
                element.style.transition = 'transform 0.15s ease-out';
                
                setTimeout(() => {
                    element.textContent = newValue;
                    element.style.transform = 'scale(1)';
                }, 150);
            }
        }

        // Format date for display
        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        }

        // Update Recent Stock In list
        function updateRecentStockIn(items) {
            const container = document.getElementById('recentStockInContainer');
            if (!container) return;

            if (!items || items.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <p class="text-slate-500">Belum ada data barang masuk</p>
                    </div>`;
                return;
            }

            container.innerHTML = items.map(item => `
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-emerald-50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">${item.item_name}</p>
                            <p class="text-sm text-slate-500">${formatDate(item.date)} - ${item.time}</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-emerald-600 bg-emerald-100 px-3 py-1 rounded-lg">+${item.quantity}</span>
                </div>
            `).join('');
        }

        // Update Recent Stock Out list
        function updateRecentStockOut(items) {
            const container = document.getElementById('recentStockOutContainer');
            if (!container) return;

            if (!items || items.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <p class="text-slate-500">Belum ada data barang keluar</p>
                    </div>`;
                return;
            }

            container.innerHTML = items.map(item => `
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-orange-50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">${item.item_name}</p>
                            <p class="text-sm text-slate-500">${formatDate(item.date)} - ${item.time}</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-orange-600 bg-orange-100 px-3 py-1 rounded-lg">-${item.quantity}</span>
                </div>
            `).join('');
        }
    </script>
</x-app-layout>
