@extends('layouts.admin')

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
            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('retail.archives') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-archive"></i> Archives
                </a>
                <a href="{{ route('retail.products.create') }}" class="btn btn-dark">
                    <i class="bi bi-plus-lg"></i> Add Product
                </a>
            </div>
        </div>

        <!-- Products by Category Section -->
        <div class="retail-products-section mb-5">
            @php
                $categories = [
                    'Feeds' => 'Feeds',
                    'Vitamins' => 'Vitamins',
                    'Medicines' => 'Medicines',
                    'Growth Additives' => 'Growth Additives'
                ];
                
                $productsByCategory = [];
                foreach ($catalog as $item) {
                    $cat = $item['category'] ?? 'Feeds';
                    if (!isset($productsByCategory[$cat])) {
                        $productsByCategory[$cat] = [];
                    }
                    $productsByCategory[$cat][] = $item;
                }
            @endphp

            @foreach ($categories as $categoryName => $categoryKey)
                @php
                    $products = $productsByCategory[$categoryKey] ?? [];
                @endphp

                <div class="retail-category-section mb-5">
                    <div class="category-header mb-3">
                        <h2 class="category-title">{{ $categoryName }}</h2>
                    </div>

                    @if (count($products) > 0)
                        <div class="row g-4">
                            @foreach ($products as $product)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="product-card" id="product-card-{{ $product['id'] }}" data-product-id="{{ $product['id'] }}">
                                        <!-- Product Image -->
                                        @if ($product['image'])
                                            <div class="product-image-wrapper">
                                                <img src="{{ Storage::url($product['image']) }}" alt="{{ $product['name'] }}" class="product-image">
                                            </div>
                                        @else
                                            <div class="product-image-wrapper product-image-placeholder">
                                                <i class="bi bi-box"></i>
                                            </div>
                                        @endif

                                        <div class="product-card-body">
                                            <div class="product-header">
                                                <h5 class="product-name">{{ $product['name'] }}</h5>
                                                <span class="stock-badge {{ $product['status'] === 'In Stock' ? 'badge-active' : 'badge-inactive' }}">
                                                    {{ $product['stock'] }} units
                                                </span>
                                            </div>

                                            <!-- Product Description -->
                                            @if ($product['description'])
                                                <p class="product-description">{{ Str::limit($product['description'], 80) }}</p>
                                            @endif

                                            <div class="product-details">
                                                <!-- Price Dropdown Selector -->
                                                <div class="price-selector-container">
                                                    <label class="price-selector-label">Select Unit Type:</label>
                                                    <select class="price-selector" onchange="updatePrice({{ $product['id'] }}, this.value)" data-product-id="{{ $product['id'] }}">
                                                        @if ($product['price_per_kilo'])
                                                            <option value="{{ $product['price_per_kilo'] }}" selected>1 Kilo - ₱ {{ number_format($product['price_per_kilo'], 2) }}</option>
                                                        @endif
                                                        @if ($product['price_per_half_kilo'])
                                                            <option value="{{ $product['price_per_half_kilo'] }}">1/2 Kilo - ₱ {{ number_format($product['price_per_half_kilo'], 2) }}</option>
                                                        @endif
                                                        @if ($product['price_per_quarter_kilo'])
                                                            <option value="{{ $product['price_per_quarter_kilo'] }}">1/4 Kilo - ₱ {{ number_format($product['price_per_quarter_kilo'], 2) }}</option>
                                                        @endif
                                                        @if ($product['price_per_sack'])
                                                            <option value="{{ $product['price_per_sack'] }}">Sack - ₱ {{ number_format($product['price_per_sack'], 2) }}</option>
                                                        @endif
                                                    </select>
                                                </div>

                                                <!-- Price Display -->
                                                <div class="price-display-container">
                                                    <div class="price-label">Price:</div>
                                                    <div class="price-value" id="price_{{ $product['id'] }}">₱ {{ number_format($product['price_per_kilo'] ?? $product['rawPrice'], 2) }}</div>
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
                                                <div style="display: flex; gap: 0.5rem; align-items: stretch;">
                                                    <a href="{{ route('retail.products.edit', $product['id']) }}" class="btn-product-action" style="flex: 1; display: flex; align-items: center; justify-content: center;">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('retail.products.destroy', $product['id']) }}" style="display: flex; flex: 1; align-items: stretch;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-product-action" style="flex: 1; display: flex; align-items: center; justify-content: center;" onclick="return confirm('Archive this product?')">
                                                            <i class="bi bi-archive"></i> Archive
                                                        </button>
                                                    </form>
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

            @if (count($catalog) === 0)
                <div class="empty-page">
                    <div class="empty-icon">
                        <i class="bi bi-box"></i>
                    </div>
                    <h3>No Products Yet</h3>
                    <p class="text-muted mb-3">Start by adding products to your retail shop</p>
                    <a href="{{ route('retail.products.create') }}" class="btn btn-dark">Add Your First Product</a>
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
                            <div class="empty-quick-sale">
                                <p class="text-muted">No items added yet. Add items from the product cards below.</p>
                            </div>
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
                            @forelse ($recentItems as $item)
                                <div class="inventory-tracker-row">
                                    <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                        <div style="flex: 1;">
                                            @if ($item['type'] === 'transaction')
                                                @php
                                                    list($product, $quantity) = explode(' x', $item['items']);
                                                @endphp
                                                <div class="inventory-item-title inventory-item-title-sm">{{ trim($product) }}</div>
                                                <div class="table-meta">Qty: {{ trim($quantity) }} to {{ $item['customer'] }}</div>
                                            @else
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
                    <h2 class="modal-title">Distribute Quick Sale</h2>
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
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--pt-text);
        }

        .subtitle-text {
            color: var(--pt-muted);
        }

        :root[data-theme="dark"] .subtitle-text {
            color: #b8c9e0 !important;
        }

        :root[data-theme="light"] .subtitle-text {
            color: #6f8096;
        }

        .retail-category-section {
            margin-bottom: 2.5rem;
        }

        .category-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .category-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--pt-text);
            margin: 0;
            text-transform: capitalize;
        }

        .product-card {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .product-card:hover {
            box-shadow: var(--pt-shadow);
            transform: translateY(-2px);
            border-color: var(--pt-accent);
        }

        /* Highlight when product is in quick sale */
        .product-card.in-quick-sale {
            border: 2px solid #10b981 !important;
            background-color: rgba(16, 185, 129, 0.1) !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25) !important;
        }

        .product-card.in-quick-sale:hover {
            border: 2px solid #059669 !important;
            background-color: rgba(16, 185, 129, 0.15) !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.35) !important;
            transform: translateY(-2px);
        }

        :root[data-theme="dark"] .product-card.in-quick-sale {
            background-color: rgba(16, 185, 129, 0.12) !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3) !important;
        }

        /* Product Image Styles */
        .product-image-wrapper {
            width: 100%;
            height: 220px;
            background: var(--pt-surface-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 1px solid var(--pt-border);
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
            opacity: 0.4;
        }

        .product-description {
            font-size: 0.85rem;
            color: var(--pt-muted);
            margin: 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            height: 100%;
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .product-name {
            font-size: 1rem;
            font-weight: 600;
            color: var(--pt-text);
            margin: 0;
            flex: 1;
        }

        .stock-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .badge-active {
            background: rgba(47, 179, 111, 0.15);
            color: var(--pt-success);
        }

        .badge-inactive {
            background: rgba(239, 91, 108, 0.15);
            color: var(--pt-accent);
        }

        .product-details {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding: 1rem 0;
            border-top: 1px solid var(--pt-border);
            border-bottom: 1px solid var(--pt-border);
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.875rem;
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
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: auto;
        }

        .product-actions > div:not(.quick-sale-input-group),
        .product-actions .btn {
            flex: 1;
            font-size: 0.85rem;
        }

        .product-actions .btn-group {
            display: flex;
            gap: 0.5rem;
        }

        /* Product Action Buttons */
        .btn-product-action {
            padding: 0.5rem 1rem;
            background: transparent;
            border: 1.5px solid var(--pt-text);
            color: var(--pt-text);
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        .btn-product-action:hover {
            background: var(--pt-text);
            color: var(--pt-surface);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-product-action:active {
            transform: scale(0.98);
        }

        /* Light Mode */
        :root[data-theme="light"] .btn-product-action {
            border-color: #3a4a5c;
            color: #3a4a5c;
        }

        :root[data-theme="light"] .btn-product-action:hover {
            background: #3a4a5c;
            color: #ffffff;
            border-color: #3a4a5c;
        }

        /* Dark Mode */
        :root[data-theme="dark"] .btn-product-action {
            border-color: #e0e6f0;
            color: #e0e6f0;
        }

        :root[data-theme="dark"] .btn-product-action:hover {
            background: #e0e6f0;
            color: #1a1f2e;
            border-color: #e0e6f0;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--pt-muted);
        }

        .empty-page {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-page h3 {
            color: var(--pt-text);
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--pt-muted);
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Quick Sale Control Card */
        .control-card {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 0.75rem;
            box-shadow: var(--pt-shadow);
            height: 100%;
        }

        .control-card-header {
            border-bottom: 1px solid var(--pt-border);
            padding: 1.5rem;
        }

        .control-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--pt-text);
            margin: 0;
        }

        .control-card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .control-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--pt-text);
            text-transform: capitalize;
        }

        .price-input-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--pt-surface-soft);
            border-radius: 0.5rem;
            padding: 0.5rem;
            border: 1px solid var(--pt-border);
        }

        .price-btn {
            width: 2.5rem;
            height: 2.5rem;
            border: none;
            background: var(--pt-accent);
            color: white;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .price-btn:hover {
            background: var(--pt-primary);
            transform: scale(1.05);
        }

        :root[data-theme="dark"] .price-btn {
            background: var(--pt-accent);
            color: white;
        }

        :root[data-theme="dark"] .price-btn:hover {
            background: #ff8da0;
            color: white;
        }

        .price-input {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--pt-text);
            text-align: center;
        }

        .price-input:focus {
            outline: none;
        }

        .quantity-input {
            border: 1px solid var(--pt-border) !important;
            background: var(--pt-surface-soft);
            color: var(--pt-text);
            font-weight: 500;
        }

        .quantity-input:focus {
            border-color: var(--pt-accent) !important;
            box-shadow: 0 0 0 3px rgba(239, 91, 108, 0.1);
        }

        .total-group {
            background: linear-gradient(135deg, var(--pt-primary) 0%, var(--pt-primary) 100%);
            padding: 1rem;
            border-radius: 0.5rem;
            gap: 0.5rem;
        }

        :root[data-theme="light"] .total-group {
            background: linear-gradient(135deg, #243b53 0%, #1a2d42 100%);
        }

        :root[data-theme="dark"] .total-group {
            background: linear-gradient(135deg, #243b53 0%, #1a2d42 100%);
        }

        .total-group .control-label {
            color: white;
        }

        .total-amount {
            font-size: 2rem;
            font-weight: 700;
            color: white;
        }

        .control-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .action-btn {
            flex: 1;
            padding: 0.75rem;
            border: 2px solid var(--pt-border);
            background: transparent;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            color: var(--pt-text);
        }

        .raiser-btn {
            color: var(--pt-text);
        }

        .raiser-btn:hover {
            background: var(--pt-surface-soft);
            border-color: var(--pt-primary);
        }

        .customer-btn {
            color: var(--pt-text);
        }

        .customer-btn:hover {
            background: var(--pt-surface-soft);
            border-color: var(--pt-accent);
        }

        /* Dark mode specific fixes */
        :root[data-theme="dark"] .product-card {
            background: var(--pt-surface);
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .product-name,
        :root[data-theme="dark"] .control-title,
        :root[data-theme="dark"] .category-title,
        :root[data-theme="dark"] .page-title {
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .action-btn {
            border-color: var(--pt-border);
            color: var(--pt-text);
            background: var(--pt-surface-soft);
        }

        :root[data-theme="dark"] .action-btn:hover {
            background: var(--pt-border);
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .control-label {
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .detail-label {
            color: var(--pt-muted);
        }

        :root[data-theme="dark"] .detail-value {
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .price-input {
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .quantity-input {
            background: var(--pt-surface-soft) !important;
            color: var(--pt-text) !important;
            border-color: var(--pt-border) !important;
        }

        :root[data-theme="dark"] .quantity-input::placeholder {
            color: var(--pt-muted);
        }

        :root[data-theme="dark"] .control-card {
            background: var(--pt-surface);
            border-color: var(--pt-border);
        }

        :root[data-theme="dark"] .inventory-tracker-row .inventory-item-title {
            color: var(--pt-text);
        }

        /* Additional dark mode text fixes */
        :root[data-theme="dark"] .text-muted {
            color: #b8c9e0 !important;
        }

        :root[data-theme="light"] .text-muted {
            color: #6f8096 !important;
        }

        /* Button Styling */
        .btn-dark {
            background: var(--pt-primary);
            color: white;
            border: none;
        }

        .btn-dark:hover {
            background: color-mix(in srgb, var(--pt-primary) 85%, black);
            color: white;
        }

        :root[data-theme="dark"] .btn-dark {
            background: white;
            color: var(--pt-primary);
            border: none;
        }

        :root[data-theme="dark"] .btn-dark:hover {
            background: #f0f0f0;
            color: var(--pt-primary);
        }

        .btn-outline-primary {
            border: 1px solid var(--pt-primary);
            color: var(--pt-primary);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--pt-primary);
            color: white;
        }

        :root[data-theme="dark"] .btn-outline-primary {
            border: 1px solid var(--pt-primary);
            color: var(--pt-primary);
        }

        :root[data-theme="dark"] .btn-outline-primary:hover {
            background: var(--pt-primary);
            color: white;
        }

        .btn-outline-danger {
            border: 1px solid #dc3545;
            color: #dc3545;
            background: transparent;
        }

        .btn-outline-danger:hover {
            background: #dc3545;
            color: white;
        }

        :root[data-theme="dark"] .btn-outline-danger {
            border: 1px solid #ff6b7a;
            color: #ff6b7a;
        }

        :root[data-theme="dark"] .btn-outline-danger:hover {
            background: #ff6b7a;
            color: white;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 1.5rem;
            }

            .category-title {
                font-size: 1rem;
            }

            .product-card-body {
                padding: 1rem;
            }

            .control-card-body {
                gap: 1rem;
            }

            .control-actions {
                flex-direction: column;
            }
        }

        /* Quick Sale Styles */
        .quick-sale-input-group {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
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
            border-color: #2fb36f;
            box-shadow: 0 0 0 3px rgba(47, 179, 111, 0.1);
        }

        .quick-sale-input-group .quick-sale-qty::placeholder {
            color: var(--pt-muted);
            font-weight: 400;
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

        :root[data-theme="dark"] .quick-sale-input-group .btn {
            background: linear-gradient(135deg, #2fb36f 0%, #27945a 100%);
        }

        :root[data-theme="dark"] .quick-sale-input-group .btn:hover {
            background: linear-gradient(135deg, #27945a 0%, #1f7149 100%);
        }

        .quick-sale-items-list {
            border: 1px solid var(--pt-border);
            border-radius: 0.5rem;
            padding: 1rem;
            max-height: 400px;
            overflow-y: auto;
        }

        .quick-sale-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid var(--pt-border);
            background: var(--pt-surface-soft);
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .quick-sale-item-row:last-child {
            border-bottom: none;
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
            font-size: 0.875rem;
            color: var(--pt-muted);
        }

        .quick-sale-item-controls {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .quick-sale-item-qty {
            width: 70px;
            padding: 0.5rem;
            border: 1px solid var(--pt-border);
            border-radius: 0.375rem;
            text-align: center;
        }

        .quick-sale-item-total {
            font-weight: 600;
            min-width: 100px;
            text-align: right;
            color: var(--pt-text);
        }

        .quick-sale-item-remove {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
        }

        .quick-sale-item-remove:hover {
            color: #c82333;
        }

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

        .quick-sale-customer-info {
            border: 1px solid var(--pt-border);
            padding: 1rem;
            border-radius: 0.5rem;
            background: var(--pt-surface-soft);
        }

        .quick-sale-customer-info .form-group {
            margin-bottom: 1rem;
        }

        .quick-sale-customer-info .form-group:last-child {
            margin-bottom: 0;
        }

        .empty-quick-sale {
            text-align: center;
            padding: 2rem;
            color: var(--pt-muted);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group .control-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--pt-text);
        }

        .form-group .form-control {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--pt-border);
            border-radius: 0.375rem;
            background: var(--pt-surface);
            color: var(--pt-text);
            font-size: 0.95rem;
        }

        .form-group .form-control:focus {
            outline: none;
            border-color: var(--pt-primary);
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
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

        :root[data-theme="dark"] .quick-sale-item-row {
            background: var(--pt-surface-soft);
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .form-group .form-control {
            background: var(--pt-surface-soft);
            color: var(--pt-text);
            border-color: var(--pt-border);
        }

        /* Modal Styles */
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
            z-index: 9999;
        }

        .modal-content {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 0.75rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--pt-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--pt-text);
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--pt-muted);
            cursor: pointer;
            padding: 0;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: var(--pt-text);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-subtitle {
            color: var(--pt-text);
            font-weight: 500;
        }

        .distribution-options {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .option-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding: 1rem;
            border: 1px solid var(--pt-border);
            border-radius: 0.5rem;
            background: var(--pt-surface-soft);
        }

        .option-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            color: var(--pt-text);
            cursor: pointer;
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

        :root[data-theme="dark"] .modal-content {
            background: var(--pt-surface);
            border-color: var(--pt-border);
        }

        :root[data-theme="dark"] .modal-header,
        :root[data-theme="dark"] .modal-footer {
            border-color: var(--pt-border);
        }

        :root[data-theme="dark"] .modal-title {
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .option-group {
            background: var(--pt-surface-soft);
            border-color: var(--pt-border);
        }

        :root[data-theme="dark"] .option-label {
            color: var(--pt-text);
        }

        .price-display-base {
            position: relative;
            transition: all 0.2s ease;
        }

        /* Price Display Container */
        .price-display-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: linear-gradient(135deg, rgba(239, 91, 108, 0.08) 0%, rgba(239, 91, 108, 0.04) 100%);
            border: 2px solid rgba(239, 91, 108, 0.2);
            border-radius: 8px;
            margin: 1rem 0;
            transition: all 0.3s ease;
        }

        .price-display-container:hover {
            background: linear-gradient(135deg, rgba(239, 91, 108, 0.12) 0%, rgba(239, 91, 108, 0.06) 100%);
            border-color: rgba(239, 91, 108, 0.3);
        }

        .price-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--pt-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .price-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--pt-accent);
            transition: all 0.3s ease;
            min-width: 140px;
            text-align: right;
        }

        .price-value.updating {
            animation: priceUpdate 0.5s ease-out;
        }

        @keyframes priceUpdate {
            0% {
                transform: scale(1.1);
                opacity: 0.8;
            }
            50% {
                transform: scale(0.95);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        :root[data-theme="dark"] .price-display-container {
            background: linear-gradient(135deg, rgba(239, 91, 108, 0.12) 0%, rgba(239, 91, 108, 0.06) 100%);
            border-color: rgba(239, 91, 108, 0.25);
        }

        :root[data-theme="dark"] .price-display-container:hover {
            background: linear-gradient(135deg, rgba(239, 91, 108, 0.15) 0%, rgba(239, 91, 108, 0.08) 100%);
            border-color: rgba(239, 91, 108, 0.35);
        }

        :root[data-theme="dark"] .price-label {
            color: var(--pt-muted);
        }

        :root[data-theme="dark"] .price-value {
            color: #ff758c;
        }

        @media (max-width: 480px) {
            .price-display-container {
                flex-direction: column;
                gap: 0.75rem;
                padding: 0.75rem;
            }

            .price-label {
                font-size: 0.75rem;
            }

            .price-value {
                font-size: 1.5rem;
                text-align: center;
                min-width: auto;
            }
        }

        /* Price Selector Dropdown */
        .price-selector-container {
            margin-bottom: 0.75rem;
        }

        .price-selector-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--pt-muted);
            margin-bottom: 0.5rem;
        }

        .price-selector {
            width: 100%;
            padding: 0.5rem 0.75rem;
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 6px;
            color: var(--pt-text);
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .price-selector:hover {
            border-color: var(--pt-primary);
            background: var(--pt-surface-soft);
        }

        .price-selector:focus {
            outline: none;
            border-color: var(--pt-primary);
            box-shadow: 0 0 0 3px rgba(91, 141, 239, 0.1);
        }

        :root[data-theme="dark"] .price-selector {
            background: var(--pt-surface-soft);
            border-color: var(--pt-border);
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .price-selector:hover {
            background: var(--pt-border);
            border-color: var(--pt-primary);
        }

        :root[data-theme="dark"] .price-selector option {
            background: var(--pt-surface);
            color: var(--pt-text);
        }
    </style>

    <script>
        // Update price from dropdown selection
        function updatePrice(productId, priceValue) {
            const price = parseFloat(priceValue);
            
            if (isNaN(price) || price < 0) {
                console.error('Invalid price value:', priceValue);
                return;
            }
            
            const priceDisplay = document.getElementById('price_' + productId);
            
            if (priceDisplay) {
                // Remove and re-add animation
                priceDisplay.classList.remove('updating');
                void priceDisplay.offsetWidth;
                priceDisplay.classList.add('updating');
                
                // Update the price text
                const formattedPrice = '₱ ' + price.toFixed(2);
                priceDisplay.textContent = formattedPrice;
                
                console.log('Price updated for product', productId, ':', formattedPrice);
            }
        }

        // Quick Sale Management
        let quickSaleSession = null;
        const API_BASE = '/api/quick-sale';

        // Load quick sale session on page load
        async function initQuickSale() {
            try {
                console.log('Initializing quick sale...');
                const response = await fetch(`${API_BASE}/session`);
                console.log('Session response status:', response.status);
                
                const data = await response.json();
                console.log('Session data:', data);
                
                quickSaleSession = data.session;
                renderQuickSaleItems(data.items);
                console.log('Quick sale initialized successfully');
            } catch (error) {
                console.error('Error loading quick sale session:', error);
            }
        }

        // Add product to quick sale
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
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                console.log('CSRF Token:', csrfToken ? 'Present' : 'Missing');
                console.log('Product ID:', productId, 'Quantity:', quantity);
                
                const response = await fetch(`${API_BASE}/add-item`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity,
                    }),
                });

                const data = await response.json();
                console.log('Response:', data);
                
                if (data.success) {
                    quickSaleSession = data.session;
                    renderQuickSaleItems(data.items);
                    input.value = '';  // Clear the input after successful add
                    console.log('Item added successfully');
                } else {
                    console.error('Server error:', data.error || data);
                    alert(data.error || 'Error adding item to quick sale');
                }
            } catch (error) {
                console.error('Error adding to quick sale:', error);
                alert('Error adding item to quick sale: ' + error.message);
            }
        }

        // Remove item from quick sale
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

        // Update quick sale item quantity
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

        // Render quick sale items
        function renderQuickSaleItems(items) {
            const container = document.getElementById('quickSaleItems');
            const summary = document.getElementById('quickSaleSummary');
            const actions = document.getElementById('quickSaleActions');

            if (!items || items.length === 0) {
                container.innerHTML = '<div class="empty-quick-sale"><p class="text-muted">No items added yet. Add items from the product cards below.</p></div>';
                summary.style.display = 'none';
                actions.style.display = 'none';
                // Clear all product card highlights
                updateProductCardHighlights([]);
                return;
            }

            let html = '';
            items.forEach(item => {
                html += `
                    <div class="quick-sale-item-row">
                        <div class="quick-sale-item-details">
                            <div class="quick-sale-item-name">${item.product.name}</div>
                            <div class="quick-sale-item-price">₱ ${parseFloat(item.unit_price).toFixed(2)} each</div>
                        </div>
                        <div class="quick-sale-item-controls">
                            <input type="number" class="quick-sale-item-qty" value="${item.quantity}" min="1" 
                                   onchange="updateQuickSaleQuantity(${item.id}, this.value)">
                            <div class="quick-sale-item-total">₱ ${parseFloat(item.net_price).toFixed(2)}</div>
                            <button class="quick-sale-item-remove" onclick="removeQuickSaleItem(${item.id})" title="Remove">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;

            // Update summary
            const subtotal = items.reduce((sum, item) => sum + parseFloat(item.total_price), 0);
            const totalDiscount = items.reduce((sum, item) => sum + parseFloat(item.discount_amount), 0);
            const total = items.reduce((sum, item) => sum + parseFloat(item.net_price), 0);

            document.getElementById('subtotal').textContent = `₱ ${subtotal.toFixed(2)}`;
            document.getElementById('totalDiscount').textContent = `₱ ${totalDiscount.toFixed(2)}`;
            document.getElementById('totalAmount').textContent = `₱ ${total.toFixed(2)}`;

            summary.style.display = 'block';
            actions.style.display = 'block';

            // Update product card highlights
            updateProductCardHighlights(items);
        }

        // Update product card highlights based on quick sale items
        function updateProductCardHighlights(items) {
            const itemProductIds = new Set(items.map(item => item.retail_product_id));
            document.querySelectorAll('[data-product-id]').forEach(card => {
                const productId = parseInt(card.dataset.productId);
                if (itemProductIds.has(productId)) {
                    card.classList.add('in-quick-sale');
                } else {
                    card.classList.remove('in-quick-sale');
                }
            });
        }

        // Confirm quick sale and create transactions
        function showDistributionModal() {
            if (!quickSaleSession || !quickSaleSession.items || quickSaleSession.items.length === 0) {
                alert('No items in quick sale');
                return;
            }

            // Reset modal form
            document.querySelector('input[name="distribution"][value="raiser"]').checked = true;
            document.getElementById('distributionRaiserId').value = '';
            document.getElementById('distributionCustomerName').value = '';
            document.getElementById('distributionCustomerName').disabled = true;

            // Show modal
            document.getElementById('distributionModal').style.display = 'flex';
        }

        function closeDistributionModal() {
            document.getElementById('distributionModal').style.display = 'none';
        }

        function toggleDistributionInput() {
            const raiserRadio = document.querySelector('input[name="distribution"][value="raiser"]');
            const raiserId = document.getElementById('distributionRaiserId');
            const customerName = document.getElementById('distributionCustomerName');

            if (raiserRadio.checked) {
                raiserId.disabled = false;
                customerName.disabled = true;
                customerName.value = '';
            } else {
                raiserId.disabled = true;
                customerName.disabled = false;
                raiserId.value = '';
            }
        }

        async function proceedWithConfirmation() {
            const raiserRadio = document.querySelector('input[name="distribution"][value="raiser"]');
            const raiserId = document.getElementById('distributionRaiserId').value;
            const customerName = document.getElementById('distributionCustomerName').value;

            if (raiserRadio.checked && !raiserId) {
                alert('Please select a raiser');
                return;
            }

            if (!raiserRadio.checked && !customerName) {
                alert('Please enter customer name');
                return;
            }

            try {
                // Update session info
                await fetch(`${API_BASE}/session`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        customer_name: raiserRadio.checked ? null : customerName,
                        raiser_id: raiserRadio.checked ? parseInt(raiserId) : null,
                    }),
                });

                // Confirm quick sale
                const response = await fetch(`${API_BASE}/confirm`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                });

                const data = await response.json();
                if (data.success) {
                    alert('Quick sale confirmed! Transactions have been created.');
                    closeDistributionModal();
                    quickSaleSession = null;
                    renderQuickSaleItems([]);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert(data.error || 'Error confirming quick sale');
                }
            } catch (error) {
                console.error('Error confirming quick sale:', error);
                alert('Error confirming quick sale');
            }
        }
            } catch (error) {
                console.error('Error confirming quick sale:', error);
                alert('Error confirming quick sale');
            }
        }

        // Cancel quick sale
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
                    alert('Quick sale cancelled');
                    quickSaleSession = null;
                    document.getElementById('customerName').value = '';
                    document.getElementById('raiserId').value = '';
                    renderQuickSaleItems([]);
                }
            } catch (error) {
                console.error('Error cancelling quick sale:', error);
            }
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initQuickSale();

            // Close modal when clicking outside
            document.getElementById('distributionModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDistributionModal();
                }
            });
        });
    </script>
@endsection
