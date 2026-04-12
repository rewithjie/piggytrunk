@extends('layouts.admin')

@php
    $hideTopbarTitle = true;
@endphp

@section('content')
    <section class="bootstrap-dashboard">
        <div class="dashboard-stage">
            <h1 class="dashboard-page-title mb-5">Dashboard</h1>

            <!-- Investment Summary Cards -->
            <div class="investment-cards-container mb-5">
                <div class="investment-card">
                    <div class="investment-card-label">Total Active Investment</div>
                    <div class="investment-card-value">₱ {{ number_format($investmentSummary['totalActive']) }}</div>
                </div>

                <div class="investment-card">
                    <div class="investment-card-label">Number of Hog Batch</div>
                    <div class="investment-card-value">{{ $investmentSummary['batchCount'] }}</div>
                </div>

                <div class="investment-card">
                    <div class="investment-card-label">Total Capital Invested</div>
                    <div class="investment-card-value">₱ {{ number_format($investmentSummary['totalCapital']) }}</div>
                </div>

                <div class="investment-card">
                    <div class="investment-card-label">Hog Expenses Amount</div>
                    <div class="investment-card-value">₱ 0.00</div>
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

                    <div class="allocation-card">
                        <div class="allocation-card-label">Boar</div>
                        <div class="allocation-card-value">{{ round(($investmentSummary['allocation']['boar'] / $investmentSummary['totalCapital']) * 100) }}%</div>
                        <div class="allocation-card-amount">₱ {{ number_format($investmentSummary['allocation']['boar']) }}</div>
                    </div>
                </div>
            </div>


        </div>
    </section>

    <style>
        .dashboard-page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--pt-text);
            margin-bottom: 1.5rem;
        }

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
    </style>
@endsection
