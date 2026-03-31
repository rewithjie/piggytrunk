@extends('layouts.admin')

@section('content')
    <section class="investment-page">
        <div class="row g-4 mb-4">
            <div class="col-12 col-xl-8">
                <div class="card dashboard-bootstrap-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="section-label mb-1">Portfolio</p>
                                <h3 class="chart-title mb-0">Batch Capital Allocation</h3>
                            </div>
                            <span class="chart-badge badge text-bg-light">4 Active Batches</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle dashboard-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Batch</th>
                                        <th>Hog Raiser</th>
                                        <th>Capital</th>
                                        <th>Hogs</th>
                                        <th>Stage</th>
                                        <th>Projected ROI</th>
                                        <th>Cycle Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($batchAllocations as $batch)
                                        <tr>
                                            <td class="fw-semibold">{{ $batch['batch'] }}</td>
                                            <td>{{ $batch['raiser'] }}</td>
                                            <td class="fw-semibold">{{ $batch['capital'] }}</td>
                                            <td>{{ $batch['hog_count'] }}</td>
                                            <td>{{ $batch['stage'] }}</td>
                                            <td class="fw-semibold text-success">{{ $batch['roi'] }}</td>
                                            <td style="min-width: 180px;">
                                                <div class="d-flex justify-content-between small table-meta mb-2">
                                                    <span>{{ $batch['progress'] }}%</span>
                                                    <span>{{ $batch['stage'] }}</span>
                                                </div>
                                                <div class="progress dashboard-progress">
                                                    <div class="progress-bar dashboard-progress-bar" style="width: {{ $batch['progress'] }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
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
                                <h3 class="chart-title mb-0">Upcoming Releases</h3>
                            </div>
                        </div>

                        <div class="investment-timeline">
                            @foreach ($payoutTimeline as $item)
                                <div class="investment-timeline-item">
                                    <div class="d-flex justify-content-between gap-3 mb-2">
                                        <div>
                                            <div class="investment-timeline-title">{{ $item['title'] }}</div>
                                            <div class="table-meta">{{ $item['batch'] }}</div>
                                        </div>
                                        <span class="investment-pill">{{ $item['state'] }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between gap-3 small">
                                        <span class="fw-semibold">{{ $item['amount'] }}</span>
                                        <span class="table-meta">{{ $item['date'] }}</span>
                                    </div>
                                </div>
                            @endforeach
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
                                <p class="section-label mb-1">Investor Book</p>
                                <h3 class="chart-title mb-0">Investor Commitments</h3>
                            </div>
                            <span class="chart-badge badge text-bg-light">Updated March 27, 2026</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle dashboard-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Investor</th>
                                        <th>Tier</th>
                                        <th>Committed</th>
                                        <th>Batch</th>
                                        <th>Joined</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($investors as $investor)
                                        <tr>
                                            <td class="fw-semibold">{{ $investor['name'] }}</td>
                                            <td>{{ $investor['tier'] }}</td>
                                            <td class="fw-semibold">{{ $investor['committed'] }}</td>
                                            <td>{{ $investor['batch'] }}</td>
                                            <td>{{ $investor['joined'] }}</td>
                                            <td>
                                                <span class="badge rounded-pill status-badge {{ $investor['status'] === 'Active' ? 'status-badge-active' : 'status-badge-inactive' }}">
                                                    {{ $investor['status'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
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
                                    <h3 class="chart-title mb-0">Capital by Batch</h3>
                                    <span class="chart-badge badge text-bg-light">₱</span>
                                </div>
                                <div class="chart-wrap">
                                    <canvas id="investmentCapitalChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card dashboard-bootstrap-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="chart-title mb-0">Batch Stage Mix</h3>
                                    <span class="chart-badge badge text-bg-light">Live</span>
                                </div>
                                <div class="chart-wrap chart-wrap-donut">
                                    <canvas id="investmentStageChart"></canvas>
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
            const capitalCanvas = document.getElementById('investmentCapitalChart');
            const stageCanvas = document.getElementById('investmentStageChart');
            let capitalChart = null;
            let stageChart = null;

            const palette = () => {
                const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

                return {
                    text: isDark ? '#ecf2ff' : '#243b53',
                    muted: isDark ? '#9cb0c9' : '#6f8096',
                    grid: isDark ? '#2a3950' : '#edf0f6',
                    bars: isDark ? ['#ff8da0', '#6ea8ff', '#70e7a8', '#ffc56f'] : ['#ff6078', '#5b8def', '#2fb36f', '#ffb85c'],
                    doughnut: isDark ? ['#6ea8ff', '#ffb277', '#ff8da0'] : ['#5b8def', '#ff9a3d', '#ff6078'],
                };
            };

            const renderCharts = () => {
                const colors = palette();

                if (capitalChart) {
                    capitalChart.destroy();
                }

                if (stageChart) {
                    stageChart.destroy();
                }

                capitalChart = new Chart(capitalCanvas, {
                    type: 'bar',
                    data: {
                        labels: @json($capitalChart['labels']),
                        datasets: [{
                            data: @json($capitalChart['values']),
                            backgroundColor: colors.bars,
                            borderRadius: 14,
                            borderSkipped: false,
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

                stageChart = new Chart(stageCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: @json($stageChart['labels']),
                        datasets: [{
                            data: @json($stageChart['values']),
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
