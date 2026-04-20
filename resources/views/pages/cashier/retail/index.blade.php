@extends('layouts.cashier')

@php
    $hideTopbarTitle = true;
@endphp

@section('content')
    <section class="retail-page">
        @if (session('status'))
            <div class="alert alert-success mb-4">{{ session('status') }}</div>
        @endif

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="page-title mb-0">POS</h1>
            </div>
        </div>

        <!-- Products by Category Section -->
        <div class="retail-products-section mb-5">
            @foreach ($categories as $categoryName)
                @php
                    $products = $productsByCategory[$categoryName] ?? collect();
                @endphp

                <div class="retail-category-section mb-5">
                    <div class="category-header mb-3">
                        <h2 class="category-title">{{ $categoryName }}</h2>
                    </div>

                    @if ($products->count() > 0)
                        <div class="row g-4">
                            @foreach ($products as $product)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="product-card">
                                        <!-- Product Image -->
                                        @if ($product['image'])
                                            <div class="product-image-wrapper">
                                                <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" class="product-image">
                                            </div>
                                        @else
                                            <div class="product-image-wrapper product-image-placeholder">
                                                <i class="bi bi-box"></i>
                                            </div>
                                        @endif

                                        <div class="product-card-body">
                                            <div class="product-header">
                                                <h5 class="product-name">{{ $product['name'] }}</h5>
                                                <span class="stock-badge {{ $product['stock'] > 0 ? 'badge-active' : 'badge-inactive' }}">
                                                    {{ $product['stock'] }} units
                                                </span>
                                            </div>

                                            <!-- Product Description -->
                                            @if ($product['description'])
                                                <p class="product-description">{{ Str::limit($product['description'], 80) }}</p>
                                            @endif

                                            <div class="product-details">
                                                <div class="detail-row">
                                                    <span class="detail-label">Price:</span>
                                                    <span class="detail-value">₱{{ $product['price'] }}</span>
                                                </div>
                                                <div class="detail-row">
                                                    <span class="detail-label">Sold:</span>
                                                    <span class="detail-value">{{ $product['sales'] }}</span>
                                                </div>
                                            </div>

                                            <div class="product-actions">
                                                <div class="quick-sale-input-group">
                                                    <i class="bi bi-lightning-charge-fill quick-sale-icon"></i>
                                                    <input type="number" class="form-control quick-sale-qty" data-product-id="{{ $product['id'] }}" data-product-name="{{ $product['name'] }}" data-product-price="{{ $product['rawPrice'] }}" placeholder="0" min="1">
                                                    <button type="button" class="btn btn-sm btn-primary" onclick="addToQuickSale(this)" title="Add to Quick Sale">
                                                        <i class="bi bi-cart-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <p class="text-muted">No {{ strtolower($categoryName) }} available yet.</p>
                        </div>
                    @endif
                </div>
            @endforeach

            @php
                $totalProducts = 0;
                foreach ($productsByCategory as $products) {
                    $totalProducts += $products->count();
                }
            @endphp

            @if ($totalProducts === 0)
                <div class="empty-page">
                    <div class="empty-icon">
                        <i class="bi bi-box"></i>
                    </div>
                    <h3>No Products Yet</h3>
                    <p class="text-muted mb-3">No products are available at this time</p>
                </div>
            @endif
        </div>

        <!-- Price & Quantity Control Card -->
        <div class="row g-4 mb-5">
            <div class="col-12 col-lg-8">
                <div class="control-card">
                    <div class="control-card-header">
                        <h3 class="control-title">Quick Sale Items</h3>
                    </div>
                    <div class="control-card-body">
                        <!-- Quick Sale Items List -->
                        <div id="quickSaleItems" class="quick-sale-items-list" style="min-height: 200px;">
                            <p class="text-muted text-center py-4">No items added yet</p>
                        </div>

                        <!-- Quick Sale Summary -->
                        <div class="quick-sale-summary" id="quickSaleSummary" style="display: none;">
                            <div class="summary-row">
                                <span>Subtotal:</span>
                                <span id="subtotal">₱ 0.00</span>
                            </div>
                            <div class="summary-row">
                                <span>Discount:</span>
                                <span id="totalDiscount">₱ 0.00</span>
                            </div>
                            <div class="summary-row summary-total">
                                <span>Total:</span>
                                <span id="totalAmount">₱ 0.00</span>
                            </div>
                        </div>

                        <!-- Quick Sale Actions -->
                        <div class="quick-sale-actions" id="quickSaleActions" style="display: none;">
                            <button type="button" class="btn btn-success w-100 mb-2" onclick="showDistributionModal()">
                                <i class="bi bi-check-circle"></i> Confirm & Create Transaction
                            </button>
                            <button type="button" class="btn btn-outline-danger w-100" onclick="cancelQuickSale()">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Section -->
            <div class="col-12 col-lg-4">
                <div class="card dashboard-bootstrap-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="section-label mb-1">Recent Activity</p>
                                <h3 class="chart-title mb-0">Transactions & Updates</h3>
                            </div>
                        </div>

                        <div class="inventory-tracker-list">
                            @forelse ($orders->concat($activities ?? collect())->sortByDesc('date')->take(10) as $item)
                                <div class="inventory-tracker-row">
                                    <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                        <div style="flex: 1;">
                                            @if ($item['type'] === 'transaction')
                                                @php
                                                    list($product, $quantity) = explode(' x', $item['items']);
                                                @endphp
                                                <div class="inventory-item-title inventory-item-title-sm">{{ trim($product) }}</div>
                                                <div class="table-meta">Qty: {{ trim($quantity) }} to {{ $item['customer'] }}</div>
                                            @elseif ($item['type'] === 'activity')
                                                <div class="inventory-item-title inventory-item-title-sm">{{ $item['description'] }}</div>
                                                <div class="table-meta">Product Update</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">{{ $item['date'] }}</small>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No transactions yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribution Modal -->
        <div id="distributionModal" class="modal-overlay" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Distribute Sale</h2>
                    <button type="button" class="modal-close" onclick="closeDistributionModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="modal-subtitle mb-4">Who will receive this transaction?</p>
                    
                    <div class="distribution-options">
                        <div class="option-group">
                            <label class="option-label">
                                <input type="radio" name="distribution" value="raiser" checked onchange="toggleDistributionInput()">
                                <span>Distribute to Raiser</span>
                            </label>
                            <select id="distributionRaiserId" class="form-control mt-2">
                                <option value="">-- Select Raiser --</option>
                                @foreach ($raisers as $raiser)
                                    <option value="{{ $raiser->id }}">{{ $raiser->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="option-group">
                            <label class="option-label">
                                <input type="radio" name="distribution" value="customer" onchange="toggleDistributionInput()">
                                <span>Distribute to Customer</span>
                            </label>
                            <input type="text" id="distributionCustomerName" class="form-control mt-2" placeholder="Customer Name" disabled>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeDistributionModal()">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="proceedWithConfirmation()">Confirm & Process</button>
                </div>
            </div>
        </div>

    </section>

    <style>
        .retail-page {
            padding: 1.5rem 0;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--pt-text);
        }

        .retail-products-section {
            margin-bottom: 2rem;
        }

        .retail-category-section {
            margin-bottom: 3rem;
        }

        .category-header {
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--pt-border);
            margin-bottom: 1.5rem;
        }

        .category-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--pt-text);
            margin: 0;
        }

        .product-card {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 0.75rem;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            border-color: var(--pt-border);
            box-shadow: 0 4px 12px rgba(68, 85, 113, 0.15);
        }

        .product-image-wrapper {
            width: 100%;
            height: 200px;
            background: var(--pt-surface-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-image-placeholder {
            color: var(--pt-muted);
            font-size: 3rem;
        }

        .product-card-body {
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .product-name {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: var(--pt-text);
            flex: 1;
        }

        .stock-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-active {
            background: #d1fae5;
            color: #065f46;
        }

        :root[data-theme="dark"] .badge-active {
            background: #064e3b;
            color: #d1fae5;
        }

        .badge-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        :root[data-theme="dark"] .badge-inactive {
            background: #7f1d1d;
            color: #fee2e2;
        }

        .product-description {
            font-size: 0.85rem;
            color: var(--pt-muted);
            margin: 0 0 0.75rem;
            line-height: 1.5;
        }

        .product-details {
            margin-bottom: 1rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .detail-label {
            color: var(--pt-muted);
            font-weight: 500;
        }

        .detail-value {
            color: var(--pt-text);
            font-weight: 600;
        }

        .product-actions {
            margin-top: auto;
        }

        .quick-sale-input-group {
            display: flex;
            gap: 0.75rem;
            padding: 1rem;
            background: linear-gradient(135deg, rgba(47, 179, 111, 0.1) 0%, rgba(47, 179, 111, 0.05) 100%);
            border: 2px solid rgba(47, 179, 111, 0.3);
            border-radius: 0.75rem;
            align-items: center;
        }

        .quick-sale-icon {
            color: var(--pt-success);
            font-size: 1.2rem;
        }

        :root[data-theme="dark"] .quick-sale-input-group {
            background: linear-gradient(135deg, rgba(47, 179, 111, 0.15) 0%, rgba(47, 179, 111, 0.08) 100%);
            border-color: rgba(47, 179, 111, 0.4);
        }

        .quick-sale-input-group .quick-sale-qty {
            width: 80px;
            padding: 0.75rem;
            border: 1px solid var(--pt-border);
            border-radius: 0.5rem;
            height: auto;
            font-weight: 600;
            font-size: 1rem;
            text-align: center;
            background: var(--pt-surface);
            color: var(--pt-text);
        }

        /* Remove spinner arrows from number input */
        .quick-sale-input-group .quick-sale-qty::-webkit-outer-spin-button,
        .quick-sale-input-group .quick-sale-qty::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .quick-sale-input-group .quick-sale-qty {
            -moz-appearance: textfield;
        }

        .quick-sale-input-group .quick-sale-qty:focus {
            outline: none;
            border-color: var(--pt-success);
            box-shadow: 0 0 0 3px rgba(47, 179, 111, 0.1);
        }

        .quick-sale-input-group .btn {
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            background: linear-gradient(135deg, #2fb36f 0%, #27945a 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .quick-sale-input-group .btn:hover {
            background: linear-gradient(135deg, #27945a 0%, #1f7149 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(47, 179, 111, 0.3);
        }

        .control-card {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .control-card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--pt-border);
            background: var(--pt-surface-soft);
        }

        .control-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--pt-text);
        }

        .control-card-body {
            padding: 1.5rem;
        }

        .quick-sale-items-list {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 200px;
            text-align: center;
        }

        .quick-sale-items-list p {
            margin: 0;
            width: 100%;
        }

        .quick-sale-item-row {
            padding: 1rem;
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .quick-sale-item-details {
            flex: 1;
        }

        .quick-sale-item-name {
            font-weight: 600;
            color: var(--pt-text);
            margin-bottom: 0.25rem;
        }

        .quick-sale-item-price {
            font-size: 0.9rem;
            color: var(--pt-muted);
        }

        .quick-sale-item-controls {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .quick-sale-item-qty {
            width: 60px;
            padding: 0.5rem;
            border: 1px solid var(--pt-border);
            border-radius: 0.375rem;
            background: var(--pt-surface);
            color: var(--pt-text);
            text-align: center;
            font-weight: 600;
        }

        .quick-sale-item-total {
            font-weight: 600;
            color: var(--pt-text);
            min-width: 80px;
            text-align: right;
        }

        .control-group {
            margin-bottom: 1rem;
        }

        .control-label {
            display: block;
            color: var(--pt-muted);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .control-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--pt-text);
        }

        .total-group {
            padding: 1rem;
            background: var(--pt-surface-soft);
            border: 2px solid var(--pt-border);
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .total-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--pt-text);
        }

        .control-actions {
            display: flex;
            gap: 0.75rem;
        }

        .action-btn {
            flex: 1;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: white;
        }

        .raiser-btn {
            background: var(--pt-success);
        }

        .raiser-btn:hover {
            background: #2fb36f;
            opacity: 0.8;
        }

        .customer-btn {
            background: var(--pt-accent);
        }

        .customer-btn:hover {
            background: #ef5b6c;
            opacity: 0.8;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1050;
        }

        .modal-content {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 0.75rem;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--pt-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--pt-text);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            color: var(--pt-muted);
            padding: 0;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s ease;
        }

        .modal-close:hover {
            color: var(--pt-text);
        }

        .modal-body {
            padding: 2rem 1.5rem;
        }

        .modal-subtitle {
            font-size: 0.95rem;
            color: var(--pt-text);
            font-weight: 500;
        }

        .distribution-options {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .option-group {
            padding: 1rem;
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            border-radius: 0.5rem;
        }

        .option-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            font-weight: 500;
            color: var(--pt-text);
            margin: 0;
        }

        .option-label input[type="radio"] {
            cursor: pointer;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--pt-border);
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        .btn-secondary {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            color: var(--pt-text);
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: var(--pt-border);
        }

        .btn-success {
            background: var(--pt-success);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: var(--pt-success);
            opacity: 0.8;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--pt-muted);
        }

        .empty-page {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--pt-muted);
            margin-bottom: 1rem;
        }

        .empty-page h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--pt-text);
            margin-bottom: 0.5rem;
        }

        .empty-page p {
            color: var(--pt-muted);
            margin-bottom: 1.5rem;
        }

        /* Dark Mode Text Fixes */
        :root[data-theme="dark"] .dashboard-bootstrap-card {
            background: var(--pt-surface);
            border-color: var(--pt-border);
        }

        :root[data-theme="dark"] .dashboard-bootstrap-card .card-body {
            background: var(--pt-surface);
        }

        :root[data-theme="dark"] .inventory-tracker-list {
            background: var(--pt-surface-soft);
        }

        :root[data-theme="dark"] .inventory-tracker-row {
            background: var(--pt-surface);
            border-color: var(--pt-border);
        }

        :root[data-theme="dark"] .inventory-tracker-row .inventory-item-title {
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .inventory-tracker-row .table-meta {
            color: var(--pt-muted);
        }

        :root[data-theme="dark"] .section-label {
            color: var(--pt-muted);
        }

        :root[data-theme="dark"] .chart-title {
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .text-muted {
            color: #b8c9e0 !important;
        }

        :root[data-theme="light"] .text-muted {
            color: #6f8096 !important;
        }

        /* Quick Sale Summary Styles */
        .quick-sale-summary {
            border-top: 2px solid var(--pt-border);
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            font-size: 0.95rem;
            color: var(--pt-text);
        }

        .summary-total {
            font-weight: 700;
            font-size: 1.1rem;
            border-top: 1px solid var(--pt-border);
            padding-top: 0.75rem;
            margin-top: 0.75rem;
        }

        .quick-sale-actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .btn-success {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-outline-danger {
            background: transparent;
            color: #dc3545;
            border: 1px solid #dc3545;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-outline-danger:hover {
            background: #dc3545;
            color: white;
        }

        :root[data-theme="dark"] .quick-sale-summary {
            border-color: var(--pt-border);
        }

        :root[data-theme="dark"] .summary-row {
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .summary-total {
            border-color: var(--pt-border);
        }
    </style>

    <script>
        let quickSaleSession = null;
        const API_BASE = '/api/quick-sale';

        async function initQuickSale() {
            try {
                const response = await fetch(`${API_BASE}/session`);
                const data = await response.json();
                quickSaleSession = data.session;
                renderQuickSaleItems(data.items);
            } catch (error) {
                console.error('Error loading quick sale session:', error);
            }
        }

        async function addToQuickSale(button) {
            const quickSaleGroup = button.closest('.quick-sale-input-group');
            const input = quickSaleGroup.querySelector('.quick-sale-qty');
            const productId = input.dataset.productId;
            const quantity = parseInt(input.value) || 0;

            if (quantity < 1) {
                alert('Please enter a quantity before adding to quick sale');
                input.focus();
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/add-item`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity,
                    }),
                });

                const data = await response.json();
                if (data.success) {
                    quickSaleSession = data.session;
                    renderQuickSaleItems(data.items);
                    input.value = '';
                } else {
                    alert(data.error || 'Error adding item to quick sale');
                }
            } catch (error) {
                console.error('Error adding to quick sale:', error);
                alert('Error adding item to quick sale');
            }
        }

        function renderQuickSaleItems(items) {
            const container = document.getElementById('quickSaleItems');
            const summary = document.getElementById('quickSaleSummary');
            const actions = document.getElementById('quickSaleActions');

            if (!items || items.length === 0) {
                container.innerHTML = '<p class="text-muted text-center py-4">No items added yet</p>';
                summary.style.display = 'none';
                actions.style.display = 'none';
                return;
            }

            let subtotal = 0;
            let html = '';

            items.forEach(item => {
                const itemTotal = (item.price * item.quantity) - (item.discount || 0);
                subtotal += itemTotal;

                html += `
                    <div class="quick-sale-item-row">
                        <div class="quick-sale-item-details">
                            <div class="quick-sale-item-name">${item.product_name}</div>
                            <div class="quick-sale-item-price">₱${parseFloat(item.price).toFixed(2)} × ${item.quantity}</div>
                        </div>
                        <div class="quick-sale-item-controls">
                            <input type="number" class="quick-sale-item-qty" value="${item.quantity}" min="1" onchange="updateQuickSaleQuantity(${item.id}, this.value)">
                            <div class="quick-sale-item-total">₱${itemTotal.toFixed(2)}</div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeQuickSaleItem(${item.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;

            document.getElementById('subtotal').textContent = `₱ ${subtotal.toFixed(2)}`;
            document.getElementById('totalDiscount').textContent = `₱ 0.00`;
            document.getElementById('totalAmount').textContent = `₱ ${subtotal.toFixed(2)}`;
            summary.style.display = 'block';
            actions.style.display = 'block';
        }

        async function removeQuickSaleItem(itemId) {
            if (!confirm('Remove this item from quick sale?')) return;

            try {
                const response = await fetch(`${API_BASE}/item/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                });

                const data = await response.json();
                if (data.success) {
                    quickSaleSession = data.session;
                    renderQuickSaleItems(data.items);
                }
            } catch (error) {
                console.error('Error removing item:', error);
            }
        }

        async function updateQuickSaleQuantity(itemId, newQuantity) {
            if (newQuantity < 1) {
                removeQuickSaleItem(itemId);
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/item/${itemId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        quantity: newQuantity,
                    }),
                });

                const data = await response.json();
                if (data.success) {
                    quickSaleSession = data.session;
                    renderQuickSaleItems(data.items);
                }
            } catch (error) {
                console.error('Error updating quantity:', error);
            }
        }

        function showDistributionModal() {
            document.getElementById('distributionModal').style.display = 'flex';
        }

        function closeDistributionModal() {
            document.getElementById('distributionModal').style.display = 'none';
        }

        function toggleDistributionInput() {
            const isRaiser = document.querySelector('input[name="distribution"]:checked').value === 'raiser';
            document.getElementById('distributionRaiserId').disabled = !isRaiser;
            document.getElementById('distributionCustomerName').disabled = isRaiser;
        }

        async function proceedWithConfirmation() {
            const distributionType = document.querySelector('input[name="distribution"]:checked').value;
            const raiserId = document.getElementById('distributionRaiserId').value;
            const customerName = document.getElementById('distributionCustomerName').value;

            if (distributionType === 'raiser' && !raiserId) {
                alert('Please select a raiser');
                return;
            }

            if (distributionType === 'customer' && !customerName) {
                alert('Please enter a customer name');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/confirm`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        raiser_id: distributionType === 'raiser' ? raiserId : null,
                        customer_name: distributionType === 'customer' ? customerName : null,
                    }),
                });

                const data = await response.json();
                if (data.success) {
                    alert('Sales confirmed successfully!');
                    closeDistributionModal();
                    quickSaleSession = null;
                    renderQuickSaleItems([]);
                    location.reload();
                } else {
                    alert(data.error || 'Error processing sale');
                }
            } catch (error) {
                console.error('Error confirming sale:', error);
                alert('Error processing sale');
            }
        }

        async function cancelQuickSale() {
            if (!confirm('Cancel this quick sale? All items will be removed.')) return;

            try {
                const response = await fetch(`${API_BASE}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                });

                const data = await response.json();
                if (data.success) {
                    quickSaleSession = null;
                    renderQuickSaleItems([]);
                }
            } catch (error) {
                console.error('Error canceling sale:', error);
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initQuickSale);

        // Auto-refresh products every 30 seconds
        setInterval(() => {
            fetch('{{ route("cashier.api.retail-products") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateProductsDisplay(data.productsByCategory);
                    }
                })
                .catch(error => console.error('Error fetching products:', error));
        }, 30000);

        function updateProductsDisplay(productsByCategory) {
            const categories = ['Feeds', 'Vitamins', 'Medicines', 'Growth Additives'];
            const productsContainer = document.querySelector('.retail-products-section');
            
            let html = '';
            categories.forEach(categoryName => {
                const products = productsByCategory[categoryName] || [];
                html += `
                    <div class="retail-category-section mb-5">
                        <div class="category-header mb-3">
                            <h2 class="category-title">${categoryName}</h2>
                        </div>
                `;
                
                if (products.length > 0) {
                    html += '<div class="row g-4">';
                    products.forEach(product => {
                        const stockBadge = product.stock > 0 ? 'badge-active' : 'badge-inactive';
                        const stockText = product.stock > 0 ? `${product.stock} units` : 'Out of Stock';
                        const imageHtml = product.image 
                            ? `<img src="${product.image}" alt="${product.name}" class="product-image">`
                            : `<i class="bi bi-box"></i>`;
                        
                        html += `
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="product-card">
                                    <div class="product-image-wrapper ${!product.image ? 'product-image-placeholder' : ''}">
                                        ${imageHtml}
                                    </div>
                                    <div class="product-card-body">
                                        <div class="product-header">
                                            <h5 class="product-name">${product.name}</h5>
                                            <span class="stock-badge ${stockBadge}">${stockText}</span>
                                        </div>
                        `;
                        
                        if (product.description) {
                            html += `<p class="product-description">${product.description.substring(0, 80)}</p>`;
                        }
                        
                        html += `
                                        <div class="product-details">
                                            <div class="detail-row">
                                                <span class="detail-label">Price:</span>
                                                <span class="detail-value">₱${product.price}</span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="detail-label">Sold:</span>
                                                <span class="detail-value">${product.sales}</span>
                                            </div>
                                        </div>
                                        <div class="product-actions">
                        `;
                        
                        if (['Feeds', 'Vitamins', 'Medicines'].includes(product.category)) {
                            html += `
                                <div class="quick-sale-input-group">
                                    <i class="bi bi-lightning-charge-fill quick-sale-icon"></i>
                                    <input type="number" class="form-control quick-sale-qty" data-product-id="${product.id}" data-product-name="${product.name}" data-product-price="${product.rawPrice}" placeholder="0" min="1">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addToQuickSale(this)" title="Add to Quick Sale">
                                        <i class="bi bi-cart-plus"></i> Add
                                    </button>
                                </div>
                            `;
                        }
                        
                        html += `
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                } else {
                    html += '<div class="empty-state"><p class="text-muted">No ' + categoryName.toLowerCase() + ' available yet.</p></div>';
                }
                
                html += '</div>';
            });
            
            productsContainer.innerHTML = html;
        }
    </script>
@endsection
