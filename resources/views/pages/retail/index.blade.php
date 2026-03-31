@extends('layouts.admin')

@section('content')
    <section class="retail-page">
        @if (session('status'))
            <div class="alert alert-success mb-4">{{ session('status') }}</div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-12 col-xl-8">
                <div class="card dashboard-bootstrap-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="section-label mb-1">Catalog</p>
                                <h3 class="chart-title mb-0">Retail Product Overview</h3>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="chart-badge badge text-bg-light">Feeds, Vitamins, Medicines, Growth Additives</span>
                                <a href="{{ route('retail.products.create') }}" class="btn btn-sm btn-dark">Add Product</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle dashboard-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Sold This Cycle</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($catalog as $item)
                                        <tr>
                                            <td class="fw-semibold">{{ $item['name'] }}</td>
                                            <td>{{ $item['category'] }}</td>
                                            <td class="fw-semibold">{{ $item['price'] }}</td>
                                            <td>{{ $item['stock'] }}</td>
                                            <td>
                                                <span class="badge rounded-pill status-badge {{ $item['status'] === 'In Stock' ? 'status-badge-active' : 'status-badge-inactive' }}">
                                                    {{ $item['status'] }}
                                                </span>
                                            </td>
                                            <td>{{ $item['sales'] }}</td>
                                            <td>
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('retail.products.edit', $item['id']) }}" class="btn btn-sm table-icon-btn" aria-label="Edit {{ $item['name'] }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('retail.products.destroy', $item['id']) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm table-icon-btn" aria-label="Delete {{ $item['name'] }}" onclick="return confirm('Delete this product?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if (count($catalog) === 0)
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">No products yet.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card dashboard-bootstrap-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="section-label mb-1">Top Movers</p>
                                <h3 class="chart-title mb-0">Best Sellers</h3>
                            </div>
                        </div>

                        <div class="inventory-tracker-list">
                            @foreach ($topSellers as $item)
                                <div class="inventory-tracker-row">
                                    <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                        <div>
                                            <div class="inventory-item-title inventory-item-title-sm">{{ $item['name'] }}</div>
                                            <div class="table-meta">{{ $item['category'] }}</div>
                                        </div>
                                        <span class="inventory-stock-pill">{{ $item['sold'] }} sold</span>
                                    </div>
                                    <div class="progress dashboard-progress">
                                        <div class="progress-bar dashboard-progress-bar" style="width: {{ $item['share'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                            @if (count($topSellers) === 0)
                                <p class="text-muted mb-0">No sales records yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-xl-7">
                <div class="card dashboard-bootstrap-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="section-label mb-1">Recent Transaction</p>
                                <h3 class="chart-title mb-0">New Purchases</h3>
                            </div>
                            <a href="{{ route('retail.transactions.create') }}" class="btn btn-sm btn-dark">Add Transaction</a>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle dashboard-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Items</th>
                                        <th>Channel</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="fw-semibold">{{ $order['customer'] }}</td>
                                            <td>{{ $order['items'] }}</td>
                                            <td>{{ $order['channel'] }}</td>
                                            <td class="fw-semibold">{{ $order['total'] }}</td>
                                            <td>
                                                <span class="badge rounded-pill status-badge {{ $order['status'] === 'Completed' ? 'status-badge-active' : 'status-badge-inactive' }}">
                                                    {{ $order['status'] }}
                                                </span>
                                            </td>
                                            <td>{{ $order['date'] }}</td>
                                            <td>
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('retail.transactions.edit', $order['id']) }}" class="btn btn-sm table-icon-btn" aria-label="Edit transaction">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('retail.transactions.destroy', $order['id']) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm table-icon-btn" aria-label="Delete transaction" onclick="return confirm('Delete this transaction?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if (count($orders) === 0)
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">No transactions yet.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="row g-4 h-100">
                    <div class="col-12">
                        <div class="card dashboard-bootstrap-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="chart-title mb-0">Weekly Sales</h3>
                                </div>
                                <div class="chart-wrap">
                                    <canvas id="retailSalesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card dashboard-bootstrap-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="chart-title mb-0">Sales Channel Mix</h3>
                                    <span class="chart-badge badge text-bg-light">Share</span>
                                </div>
                                <div class="chart-wrap chart-wrap-donut">
                                    <canvas id="retailChannelChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (() => {
            const salesCanvas = document.getElementById('retailSalesChart');
            const channelCanvas = document.getElementById('retailChannelChart');
            let salesChart = null;
            let channelChart = null;

            const palette = () => {
                const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

                return {
                    text: isDark ? '#ecf2ff' : '#243b53',
                    muted: isDark ? '#d2ddf0' : '#6f8096',
                    grid: isDark ? 'rgba(210, 221, 240, 0.22)' : '#edf0f6',
                    line: isDark ? '#70e7a8' : '#2fb36f',
                    fill: isDark ? 'rgba(112, 231, 168, 0.12)' : 'rgba(47, 179, 111, 0.16)',
                    doughnut: isDark ? ['#ff8da0', '#6ea8ff', '#ffc56f'] : ['#ff6078', '#5b8def', '#ffb85c'],
                };
            };

            const renderCharts = () => {
                const colors = palette();

                if (salesChart) {
                    salesChart.destroy();
                }

                if (channelChart) {
                    channelChart.destroy();
                }

                salesChart = new Chart(salesCanvas, {
                    type: 'line',
                    data: {
                        labels: @json($salesChart['labels']),
                        datasets: [{
                            data: @json($salesChart['values']),
                            borderColor: colors.line,
                            backgroundColor: colors.fill,
                            fill: true,
                            tension: 0.38,
                            pointRadius: 4,
                            pointHoverRadius: 5,
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { color: colors.muted },
                                grid: { color: colors.grid }
                            },
                            x: {
                                ticks: { color: colors.text },
                                grid: { display: false }
                            }
                        }
                    }
                });

                channelChart = new Chart(channelCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: @json($channelChart['labels']),
                        datasets: [{
                            data: @json($channelChart['values']),
                            backgroundColor: colors.doughnut,
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 10,
                                    color: colors.text
                                }
                            }
                        }
                    }
                });
            };

            renderCharts();
            window.addEventListener('themechange', renderCharts);
        })();
    </script>
@endsection
