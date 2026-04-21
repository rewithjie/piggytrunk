@extends('layouts.admin')

@section('content')
    <div class="card dashboard-bootstrap-card">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <p class="section-label mb-2" style="font-size: 1.5rem; font-weight: 600;">Investment Details</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('investments.index') }}" class="btn btn-outline-secondary">Back to Investment</a>
                </div>
            </div>

            <!-- Investment Summary Section -->
            <div class="row g-4 mb-5">
                <div class="col-12 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h3 class="table-name h4 mb-1">{{ $investment['batch_code'] }}</h3>
                            <p class="text-muted mb-3">{{ $investment['raiser_name'] }}</p>
                            <span class="badge rounded-pill status-badge {{ strtolower($investment['raiser_status']) === 'active' ? 'status-badge-active' : 'status-badge-inactive' }}">
                                {{ $investment['raiser_status'] }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="table-meta">Initial Capital</div>
                                    <div class="table-name">{{ $investment['initial_capital'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="table-meta">Current Value</div>
                                    <div class="table-name">{{ $investment['current_value'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="table-meta">Hog Type</div>
                                    <div class="table-name">{{ $investment['hog_type'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="table-meta">Total Hogs</div>
                                    <div class="table-name">{{ $investment['total_hog'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="table-meta">Investment Date</div>
                                    <div class="table-name">{{ $investment['investment_date'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="table-meta">Return Date</div>
                                    <div class="table-name">{{ $investment['expected_return_date'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Raiser Information Section -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <h5 class="mb-3" style="font-weight: 600; font-size: 1.1rem;">Raiser Information</h5>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="table-meta">Raiser Name</div>
                            <div class="table-name">{{ $investment['raiser_name'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="table-meta">Phone</div>
                            <div class="table-name">{{ $investment['raiser_phone'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="table-meta">Email</div>
                            <div class="table-name">{{ $investment['raiser_email'] ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="table-meta">Address</div>
                            <div class="table-name">{{ $investment['raiser_address'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .section-label {
            color: var(--pt-text);
        }

        .table-meta {
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--pt-muted);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .table-name {
            color: var(--pt-text);
            font-weight: 600;
            font-size: 0.95rem;
        }

        .btn-outline-secondary {
            background: transparent;
            border: 1.5px solid var(--pt-border);
            color: var(--pt-text);
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-outline-secondary:hover {
            background: var(--pt-surface);
            border-color: var(--pt-text);
            color: var(--pt-text);
        }

        .status-badge {
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.5rem 1rem;
        }

        .status-badge-active {
            background-color: #43cb89 !important;
            color: white !important;
        }

        .status-badge-inactive {
            background-color: #e74c3c !important;
            color: white !important;
        }

        .badge-stage {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: center;
        }

        .stage-completed {
            background: rgba(67, 203, 137, 0.2);
            color: #43cb89;
        }

        .stage-in-progress {
            background: rgba(91, 141, 239, 0.2);
            color: #5b8def;
        }

        .stage-pending {
            background: rgba(204, 204, 204, 0.2);
            color: #999999;
        }

        .timeline-item {
            display: flex;
            gap: 1rem;
            position: relative;
            margin-bottom: 1.5rem;
        }

        .timeline-marker {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 40px;
        }

        .timeline-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-top: 6px;
        }

        .timeline-content {
            flex: 1;
            min-width: 0;
        }

        .timeline-date {
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--pt-muted);
            font-weight: 600;
        }

        .timeline-title {
            color: var(--pt-text);
            font-size: 0.95rem;
        }
    </style>
@endsection
