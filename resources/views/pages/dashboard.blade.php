@extends('layouts.admin')

@section('content')
    <section class="bootstrap-dashboard">
        <div class="dashboard-stage">
            <div class="row g-3 mb-4 align-items-stretch summary-cards-row">
                @foreach ($stats as $stat)
                    <div class="col summary-card-col {{ $stat['label'] === 'Investment Allocation' ? 'summary-card-col-allocation' : '' }}">
                        <div class="card summary-bootstrap-card h-100">
                            <div class="card-body">
                                <div class="summary-card-head mb-3">
                                    <span class="summary-card-icon" aria-hidden="true">
                                        @if ($stat['label'] === 'Total Active Investment' || $stat['label'] === 'Total Capital Invested')
                                            <svg viewBox="0 0 24 24" fill="none">
                                                <path d="M12 4V20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                                <path d="M16 7.5C16 6.12 14.21 5 12 5C9.79 5 8 6.12 8 7.5C8 8.88 9.79 10 12 10C14.21 10 16 11.12 16 12.5C16 13.88 14.21 15 12 15C9.79 15 8 13.88 8 12.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                            </svg>
                                        @elseif ($stat['label'] === 'Number of Hog Batch')
                                            <svg viewBox="0 0 24 24" fill="none">
                                                <rect x="4.5" y="6" width="15" height="12" rx="2.5" stroke="currentColor" stroke-width="1.8" />
                                                <path d="M8 10H16M8 14H13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                            </svg>
                                        @elseif ($stat['label'] === 'Investment Allocation')
                                            <svg viewBox="0 0 24 24" fill="none">
                                                <path d="M4.5 16.5L9.2 11.8L12.2 14.8L19.5 7.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M14.5 7.5H19.5V12.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        @else
                                            <svg viewBox="0 0 24 24" fill="none">
                                                <path d="M5 15L10 10L13 13L19 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M14 7H19V12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        @endif
                                    </span>
                                    <p class="card-label mb-0">{{ $stat['label'] }}</p>
                                </div>

                                @if (isset($stat['cycles']))
                                    <div class="row g-2 text-center cycle-row">
                                        @foreach ($stat['cycles'] as $cycle)
                                            @php
                                                preg_match('/^(₱)\s+(.+)$/u', $cycle['value'], $cycleParts);
                                            @endphp
                                            <div class="col-4">
                                                <div class="cycle-caption">{{ $cycle['label'] }}</div>
                                                <div class="cycle-number">
                                                    @if (!empty($cycleParts))
                                                        <span class="cycle-number-currency">{{ $cycleParts[1] }}</span>
                                                        <span class="cycle-number-amount">{{ $cycleParts[2] }}</span>
                                                    @else
                                                        <span class="cycle-number-amount">{{ $cycle['value'] }}</span>
                                                    @endif
                                                </div>
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
                                        @if ($raisers->count() === 0)
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">No raisers matched your search.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 small text-muted pt-3">
                                @if ($raisers->total() > 0)
                                    <span>Showing {{ $raisers->firstItem() }}-{{ $raisers->lastItem() }} of {{ $raisers->total() }} raisers</span>
                                @else
                                    <span>Showing 0 raisers</span>
                                @endif

                                <div class="dashboard-pagination">
                                    {{ $raisers->onEachSide(1)->links('pagination::bootstrap-5') }}
                                </div>
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
