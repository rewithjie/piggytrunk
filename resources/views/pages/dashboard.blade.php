@extends('layouts.admin')

@section('content')
    <section class="bootstrap-dashboard">
        <div class="dashboard-stage">
            <div class="row g-3 mb-4">
                @foreach ($stats as $stat)
                    <div class="{{ isset($stat['cycles']) ? 'col-12 col-md-6 col-xl-3' : 'col-12 col-md-6 col-xl' }}">
                        <div class="card summary-bootstrap-card h-100">
                            <div class="card-body">
                                <p class="card-label mb-3">{{ $stat['label'] }}</p>

                                @if (isset($stat['cycles']))
                                    <div class="row g-3 text-center cycle-row">
                                        @foreach ($stat['cycles'] as $cycle)
                                            <div class="col-4">
                                                <div class="cycle-caption">{{ $cycle['label'] }}</div>
                                                <div class="cycle-number">{{ $cycle['value'] }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="card-number">{{ $stat['value'] }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-12 col-xl-8">
                    <div class="card dashboard-bootstrap-card h-100">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h3 class="section-heading mb-0">Hog Raiser Directory</h3>
                            </div>

                            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
                                <form method="GET" action="{{ route('dashboard') }}" class="flex-grow-1">
                                    <div class="input-group search-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input type="text" name="q" value="{{ $query }}" class="form-control border-start-0" placeholder="Search raisers...">
                                        <button type="submit" class="btn btn-dark">Search</button>
                                    </div>
                                </form>

                                <a href="{{ route('raisers.index') }}" class="btn archive-link">
                                    <i class="bi bi-funnel me-2"></i>View All Archives
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle dashboard-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Raiser Profile</th>
                                            <th>Brgy</th>
                                            <th>Active Batch</th>
                                            <th>Type of Pig</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($raisers as $raiser)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="raiser-avatar avatar-{{ $raiser->accent }}">{{ $raiser->initials }}</div>
                                                        <div>
                                                            <div class="table-name">{{ $raiser->name }}</div>
                                                            <div class="table-meta">ID: {{ $raiser->code }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $raiser->location }}</td>
                                                <td>
                                                    <span class="{{ $raiser->status === 'Active' ? 'text-danger fw-bold' : 'text-danger fw-semibold' }}">
                                                        {{ $raiser->batch }}
                                                    </span>
                                                </td>
                                                <td>{{ $raiser->pig_type }}</td>
                                                <td>
                                                    <span class="badge rounded-pill status-badge {{ strtolower($raiser->status) === 'active' ? 'status-badge-active' : 'status-badge-inactive' }}">
                                                        {{ $raiser->status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <a href="{{ route('raisers.show', $raiser->id) }}" class="btn btn-sm table-icon-btn" aria-label="View {{ $raiser->name }}">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('raisers.edit', $raiser->id) }}" class="btn btn-sm table-icon-btn" aria-label="Edit {{ $raiser->name }}">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if (count($raisers) === 0)
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">No raisers matched your search.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center small text-muted pt-3">
                                <span>Showing {{ count($raisers) }} raisers</span>
                                <a href="{{ route('raisers.index') }}" class="text-decoration-none">Page 1</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="card dashboard-bootstrap-card h-100 batch-feed-card">
                        <div class="card-body d-flex flex-column">
                            <h3 class="section-heading text-start mb-2">Batch Live Feed</h3>
                            <p class="batch-subheading mb-4">BATCH-01 DELA CRUZ FARMS</p>

                            <div class="feed-list mt-auto">
                            @foreach ($liveFeed as $item)
                                <div class="feed-block {{ !$loop->last ? 'mb-4' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="feed-title">{{ $item['label'] }}</span>
                                        <span class="feed-value">{{ $item['count'] }}</span>
                                    </div>
                                    <div class="progress dashboard-progress" role="progressbar" aria-valuenow="{{ $item['width'] }}" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar dashboard-progress-bar" style="width: {{ $item['width'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-1">
                <div class="col-12 col-xl-4">
                    <div class="card dashboard-bootstrap-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="chart-title mb-0">Raiser Status</h3>
                                <span class="badge text-bg-light chart-badge">Live</span>
                            </div>
                            <div class="chart-wrap chart-wrap-donut">
                                <canvas id="raiserStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <div class="card dashboard-bootstrap-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="chart-title mb-0">Hog Cycle Distribution</h3>
                                <span class="badge text-bg-light chart-badge">Batch-01</span>
                            </div>
                            <div class="chart-wrap">
                                <canvas id="hogStageChart"></canvas>
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
            const statusCanvas = document.getElementById('raiserStatusChart');
            const stageCanvas = document.getElementById('hogStageChart');
            let statusChartInstance = null;
            let stageChartInstance = null;

            const themePalette = () => {
                const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

                return {
                    text: isDark ? '#ecf2ff' : '#243b53',
                    muted: isDark ? '#9cb0c9' : '#6f8096',
                    grid: isDark ? '#2a3950' : '#edf0f6',
                    doughnut: isDark ? ['#43cb89', '#42526b', '#ff8da0'] : ['#2fb36f', '#cfd7e6', '#ff8a9a'],
                    bars: isDark ? ['#ff7f96', '#ff9b7d', '#6ea8ff'] : ['#ff6b81', '#ff8c69', '#5b8def'],
                };
            };

            const renderCharts = () => {
                const palette = themePalette();

                if (statusChartInstance) {
                    statusChartInstance.destroy();
                }

                if (stageChartInstance) {
                    stageChartInstance.destroy();
                }

                if (statusCanvas) {
                    statusChartInstance = new Chart(statusCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: @json($statusChart['labels']),
                            datasets: [{
                                data: @json($statusChart['values']),
                                backgroundColor: palette.doughnut,
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
                                        color: palette.text
                                    }
                                }
                            }
                        }
                    });
                }

                if (stageCanvas) {
                    stageChartInstance = new Chart(stageCanvas, {
                        type: 'bar',
                        data: {
                            labels: @json($stageChart['labels']),
                            datasets: [{
                                label: 'Hogs',
                                data: @json($stageChart['values']),
                                backgroundColor: palette.bars,
                                borderRadius: 12,
                                borderSkipped: false,
                            }]
                        },
                        options: {
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { color: palette.muted, precision: 0 },
                                    grid: { color: palette.grid }
                                },
                                x: {
                                    ticks: { color: palette.text },
                                    grid: { display: false }
                                }
                            },
                            plugins: {
                                legend: { display: false }
                            }
                        }
                    });
                }
            };

            renderCharts();
            window.addEventListener('themechange', renderCharts);
        })();
    </script>
@endsection
