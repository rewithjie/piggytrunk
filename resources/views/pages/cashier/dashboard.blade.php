@extends('layouts.cashier')

@php
    $pageTitle = 'Dashboard';
@endphp

@section('content')
    <section class="bootstrap-dashboard">
        <div class="dashboard-stage">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div>
                            <h1 class="page-title mb-2">Welcome, {{ session('cashier_name') }}! 👋</h1>
                            <p class="subtitle-text">Ready to process sales and manage inventory?</p>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 mb-5">
                        <div class="col">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(47, 179, 111, 0.1) 0%, rgba(47, 179, 111, 0.05) 100%);">
                                    <i class="bi bi-lightning-fill" style="color: #2fb36f;"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Quick Sales Today</div>
                                    <div class="stat-value">0</div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(36, 59, 83, 0.1) 0%, rgba(36, 59, 83, 0.05) 100%);">
                                    <i class="bi bi-bag-fill" style="color: #243b53;"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Total Revenue</div>
                                    <div class="stat-value">₱0.00</div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(255, 159, 64, 0.1) 0%, rgba(255, 159, 64, 0.05) 100%);">
                                    <i class="bi bi-box-seam-fill" style="color: #ff9f40;"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Products Available</div>
                                    <div class="stat-value">0</div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(100, 150, 200, 0.1) 0%, rgba(100, 150, 200, 0.05) 100%);">
                                    <i class="bi bi-archive-fill" style="color: #6496c8;"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Inventory Items</div>
                                    <div class="stat-value">0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row">
                        <div class="col-12">
                            <h2 class="section-title mb-3">Quick Actions</h2>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cashier.retail') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="bi bi-clipboard-check"></i>
                                </div>
                                <div class="action-content">
                                    <h3 class="action-title">Retail Sales</h3>
                                    <p class="action-desc">Process quick sales and transactions</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cashier.inventory') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="bi bi-bag"></i>
                                </div>
                                <div class="action-content">
                                    <h3 class="action-title">Inventory</h3>
                                    <p class="action-desc">View and manage stock levels</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="#" class="action-card" onclick="event.preventDefault(); alert('Coming soon!');">
                                <div class="action-icon">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <div class="action-content">
                                    <h3 class="action-title">Reports</h3>
                                    <p class="action-desc">View daily sales reports</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="#" class="action-card" onclick="event.preventDefault(); alert('Coming soon!');">
                                <div class="action-icon">
                                    <i class="bi bi-gear"></i>
                                </div>
                                <div class="action-content">
                                    <h3 class="action-title">Settings</h3>
                                    <p class="action-desc">Manage your profile settings</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Getting Started -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="getting-started">
                                <h2 class="section-title mb-3">Getting Started</h2>
                                <div class="start-card">
                                    <div style="display: flex; gap: 1.5rem;">
                                        <div style="flex-shrink: 0;">
                                            <div class="start-number">1</div>
                                        </div>
                                        <div>
                                            <h3 class="start-title">Manage Retail Sales</h3>
                                            <p class="start-desc">Go to Retail Sales to add products to quick sale cart, apply discounts, and confirm transactions with customer or raiser distribution.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="start-card">
                                    <div style="display: flex; gap: 1.5rem;">
                                        <div style="flex-shrink: 0;">
                                            <div class="start-number">2</div>
                                        </div>
                                        <div>
                                            <h3 class="start-title">Check Inventory</h3>
                                            <p class="start-desc">Monitor available inventory items and stock levels in the Inventory section to ensure you have products available for sale.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="start-card">
                                    <div style="display: flex; gap: 1.5rem;">
                                        <div style="flex-shrink: 0;">
                                            <div class="start-number">3</div>
                                        </div>
                                        <div>
                                            <h3 class="start-title">Process Transactions</h3>
                                            <p class="start-desc">Every sale will automatically update inventory and create transaction records for accounting purposes.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--pt-text);
        }

        .subtitle-text {
            color: var(--pt-muted);
            font-size: 1rem;
            margin: 0;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--pt-text);
            margin: 0;
        }

        .stat-card {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 0.75rem;
            padding: 1.5rem;
            display: flex;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            box-shadow: var(--pt-shadow);
            transform: translateY(-2px);
            border-color: var(--pt-accent);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--pt-muted);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--pt-text);
        }

        .action-card {
            background: var(--pt-surface);
            border: 2px solid var(--pt-border);
            border-radius: 0.75rem;
            padding: 2rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
            min-height: 140px;
        }

        .action-card:hover {
            border-color: var(--pt-accent);
            box-shadow: 0 8px 20px rgba(47, 179, 111, 0.15);
            transform: translateY(-4px);
        }

        .action-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(47, 179, 111, 0.1) 0%, rgba(47, 179, 111, 0.05) 100%);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #2fb36f;
            flex-shrink: 0;
        }

        .action-content {
            flex: 1;
        }

        .action-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--pt-text);
            margin: 0 0 0.5rem 0;
        }

        .action-desc {
            font-size: 0.85rem;
            color: var(--pt-muted);
            margin: 0;
        }

        .getting-started {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 0.75rem;
            padding: 2rem;
        }

        .start-card {
            padding: 1.5rem;
            border-bottom: 1px solid var(--pt-border);
        }

        .start-card:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .start-card:first-child {
            padding-top: 0;
        }

        .start-number {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #2fb36f 0%, #27945a 100%);
            color: white;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .start-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--pt-text);
            margin: 0 0 0.5rem 0;
        }

        .start-desc {
            color: var(--pt-muted);
            margin: 0;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        :root[data-theme="dark"] .stat-card,
        :root[data-theme="dark"] .action-card,
        :root[data-theme="dark"] .getting-started {
            background: var(--pt-surface);
            border-color: var(--pt-border);
        }
    </style>
@endsection
