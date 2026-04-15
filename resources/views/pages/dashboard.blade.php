@extends('layouts.admin')

@php
    $hideTopbarTitle = true;
@endphp

@section('content')
    <section class="bootstrap-dashboard">
        <div class="dashboard-stage">
            <h1 class="page-title mb-5">Dashboard</h1>

            <!-- Investment Summary Cards -->
            <div class="investment-cards-container mb-5">
                <div class="investment-card">
                    <div class="investment-card-label">Start of Investment</div>
                    <div class="investment-card-value">₱ {{ number_format($investmentSummary['totalActive']) }}</div>
                </div>

                <div class="investment-card">
                    <div class="investment-card-label">Number of Hog Batch</div>
                    <div class="investment-card-value">{{ $investmentSummary['batchCount'] }}</div>
                </div>

                <div class="investment-card">
                    <div class="investment-card-label">Total Current Investment</div>
                    <div class="investment-card-value">₱ {{ number_format($investmentSummary['totalCapital']) }}</div>
                </div>

                <div class="investment-card">
                    <div class="investment-card-label">Number of Mortality</div>
                    <div class="investment-card-value">0</div>
                </div>

                <div class="investment-card">
                    <div class="investment-card-label">Expected Profit Return</div>
                    <div class="investment-card-value">₱ {{ number_format($investmentSummary['expectedProfit']) }}</div>
                </div>
            </div>

            <!-- Investment Allocation Section -->
            <div class="investment-allocation-section mb-5">
                <h3 class="allocation-section-title mb-4">Investment Allocation</h3>
                <div class="allocation-cards-container">
                    <div class="allocation-card">
                        <div class="allocation-card-label">Fattening</div>
                        <div class="allocation-card-value">{{ round(($investmentSummary['allocation']['fattening'] / $investmentSummary['totalCapital']) * 100) }}%</div>
                        <div class="allocation-card-amount">₱ {{ number_format($investmentSummary['allocation']['fattening']) }}</div>
                    </div>

                    <div class="allocation-card">
                        <div class="allocation-card-label">Sow</div>
                        <div class="allocation-card-value">{{ round(($investmentSummary['allocation']['sow'] / $investmentSummary['totalCapital']) * 100) }}%</div>
                        <div class="allocation-card-amount">₱ {{ number_format($investmentSummary['allocation']['sow']) }}</div>
                    </div>
                </div>
            </div>

            <!-- Raiser Lifecycle Section -->
            <div class="raiser-lifecycles-section mb-5">
                <div class="raiser-cards-container">
                    @foreach ($raiserLifecycles as $raiserId => $raiserData)
                        <div class="raiser-lifecycle-card">
                            <div class="raiser-card-header">
                                <div class="raiser-info">
                                    <h4 class="raiser-card-title">{{ $raiserData['name'] }}</h4>
                                    @php
                                        $categoryName = $raisers->find($raiserId)?->pig_type ?? 'Fattening';
                                    @endphp
                                    <div class="raiser-pig-type">{{ strtoupper($categoryName) }}</div>
                                </div>
                                <div class="raiser-action-buttons">
                                    <a href="{{ route('raisers.show', $raiserId) }}" class="btn btn-dark btn-sm" title="View Profile">View Profile</a>
                                </div>
                            </div>

                            <div class="raiser-lifecycle-body">
                                @php
                                    $categories = $raiserData['categories'];
                                @endphp

                                <div class="lifecycle-category">
                                    <div class="lifecycle-timeline">
                                        <svg class="timeline-line" viewBox="0 0 800 60" preserveAspectRatio="none">
                                            <line x1="0" y1="30" x2="800" y2="30" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                        <div class="lifecycle-stages">
                                            @foreach ($categories as $index => $stage)
                                                @php
                                                    $stagePosition = ($index / (count($categories) - 1)) * 100;
                                                @endphp
                                                <div class="lifecycle-stage" style="left: {{ $stagePosition }}%">
                                                    <div class="stage-icon stage-icon-{{ $stage['status'] }}">
                                                        @if ($stage['status'] === 'completed')
                                                            <i class="bi bi-check-lg"></i>
                                                        @elseif ($stage['status'] === 'in-progress')
                                                            <i class="bi bi-exclamation-circle"></i>
                                                        @else
                                                            <i class="bi bi-circle"></i>
                                                        @endif
                                                    </div>
                                                    <div class="stage-label">
                                                        <div class="stage-name">{{ $stage['label'] }}</div>
                                                        <div class="stage-duration">{{ $stage['duration'] }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    <style>
        /* Investment Cards Container */
        .investment-cards-container {
            display: flex;
            gap: 1.5rem;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .investment-card {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 12px;
            padding: 1.5rem;
            flex: 1;
            min-width: 180px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .investment-card-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--pt-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
        }

        .investment-card-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--pt-text);
        }

        /* Investment Allocation Section */
        .investment-allocation-section {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 12px;
            padding: 2rem;
        }

        .allocation-section-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--pt-text);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .allocation-cards-container {
            display: flex;
            gap: 1.5rem;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .allocation-card {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            border-radius: 12px;
            padding: 1.5rem;
            flex: 1;
            min-width: 180px;
            border-top: 4px solid var(--pt-primary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        :root[data-theme="dark"] .allocation-card {
            border-top-color: #7c9ed2;
        }

        .allocation-card-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--pt-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
        }

        .allocation-card-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--pt-text);
            margin-bottom: 0.5rem;
        }

        .allocation-card-amount {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--pt-text);
        }

        @media (max-width: 768px) {
            .investment-cards-container,
            .allocation-cards-container {
                gap: 1rem;
            }

            .investment-card,
            .allocation-card {
                padding: 1.25rem;
                min-width: 150px;
            }

            .investment-card-value {
                font-size: 1.5rem;
            }

            .allocation-card-value {
                font-size: 1.5rem;
            }

            .investment-allocation-section {
                padding: 1.5rem;
            }
        }

        /* Raiser Lifecycle Section */
        .raiser-lifecycles-section {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 12px;
            padding: 2rem;
        }

        .lifecycles-section-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--pt-text);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .raiser-cards-container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .raiser-lifecycle-card {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .raiser-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--pt-border);
        }

        .raiser-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .raiser-card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--pt-text);
            margin: 0;
        }

        .raiser-pig-type {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--pt-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .raiser-action-buttons {
            display: flex;
            gap: 0.75rem;
        }

        .raiser-lifecycle-body {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .lifecycle-category {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .lifecycle-timeline {
            position: relative;
            padding: 2rem 0;
            min-height: 120px;
        }

        .timeline-line {
            position: absolute;
            top: 30px;
            left: 0;
            right: 0;
            width: 100%;
            height: 2px;
            color: #cbd5e1;
        }

        :root[data-theme="dark"] .timeline-line {
            color: #475569;
        }

        .lifecycle-stages {
            position: relative;
            height: 100px;
            display: flex;
            justify-content: space-between;
            padding: 0;
        }

        .lifecycle-stage {
            position: absolute;
            display: flex;
            flex-direction: column;
            align-items: center;
            transform: translateX(-50%);
        }

        .stage-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            border: 3px solid currentColor;
            transition: all 0.3s ease;
        }

        .stage-icon-completed {
            background-color: #10b981;
            color: white;
            border-color: #059669;
        }

        .stage-icon-in-progress {
            background-color: #f97316;
            color: white;
            border-color: #ea580c;
        }

        .stage-icon-pending {
            background-color: #64748b;
            color: #cbd5e1;
            border-color: #475569;
        }

        :root[data-theme="dark"] .stage-icon-pending {
            background-color: #64748b;
            color: #cbd5e1;
            border-color: #475569;
        }

        .stage-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            width: 70px;
            text-align: center;
            word-wrap: break-word;
            overflow: hidden;
        }

        .stage-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.2;
        }

        :root[data-theme="dark"] .stage-name {
            color: #e2e8f0;
        }

        .stage-duration {
            font-size: 0.7rem;
            color: #64748b;
            line-height: 1.2;
        }

        :root[data-theme="dark"] .stage-duration {
            color: #94a3b8;
        }

        @media (max-width: 768px) {
            .raiser-card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .lifecycle-timeline {
                padding: 2.5rem 1.5rem 2rem 1.5rem;
                min-height: 130px;
            }

            .timeline-line {
                left: 1.5rem;
                right: 1.5rem;
                width: calc(100% - 3rem);
            }

            .stage-icon {
                width: 40px;
                height: 40px;
                font-size: 1.25rem;
            }

            .stage-label {
                width: 65px;
            }

            .stage-name {
                font-size: 0.7rem;
            }

            .stage-duration {
                font-size: 0.6rem;
            }

            .raiser-lifecycle-card {
                padding: 1.5rem;
            }
        }
    </style>
@endsection
