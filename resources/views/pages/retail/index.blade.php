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
                <h1 class="page-title mb-0">Retail Shop</h1>
            </div>
            <a href="{{ route('retail.products.create') }}" class="btn btn-dark">
                <i class="bi bi-plus-lg"></i> Add Product
            </a>
        </div>

        <!-- Products by Category Section -->
        <div class="retail-products-section mb-5">
            @php
                $categories = [
                    'Feeds' => 'feeds',
                    'Vitamins' => 'vitamins',
                    'Medicines' => 'medicines',
                    'Growth Additives' => 'growth-additives'
                ];
                
                $productsByCategory = [];
                foreach ($catalog as $item) {
                    $cat = $item['category'] ?? 'feeds';
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
                        <h2 class="category-title">{{ $categoryName }}'s</h2>
                    </div>

                    @if (count($products) > 0)
                        <div class="row g-4">
                            @foreach ($products as $product)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="product-card">
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
                                                <div class="detail-row">
                                                    <span class="detail-label">Price:</span>
                                                    <span class="detail-value">{{ $product['price'] }}</span>
                                                </div>
                                                <div class="detail-row">
                                                    <span class="detail-label">Sold:</span>
                                                    <span class="detail-value">{{ $product['sales'] }}</span>
                                                </div>
                                            </div>

                                            <div class="product-actions">
                                                <a href="{{ route('retail.products.edit', $product['id']) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form method="POST" action="{{ route('retail.products.destroy', $product['id']) }}" style="display: inline-block; flex: 1;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Delete this product?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
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
            <div class="col-12 col-lg-6">
                <div class="control-card">
                    <div class="control-card-header">
                        <h3 class="control-title">Quick Sale</h3>
                    </div>
                    <div class="control-card-body">
                        <div class="control-group">
                            <label class="control-label">Set Quantity</label>
                            <input type="number" id="quantityInput" value="0" class="form-control quantity-input" min="0">
                        </div>

                        <div class="control-group">
                            <label class="control-label">Kilo</label>
                            <input type="text" id="kiloInput" value="0" class="form-control quantity-input" placeholder="Enter kilo weight">
                        </div>

                        <div class="control-group total-group">
                            <label class="control-label">Total</label>
                            <div class="total-amount" id="totalAmount">0.00</div>
                        </div>

                        <div class="control-actions">
                            <button class="action-btn raiser-btn" onclick="selectRaiser()">
                                Tag Raiser
                            </button>
                            <button class="action-btn customer-btn" onclick="selectCustomer()">
                                Customer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Section -->
            <div class="col-12 col-lg-6">
                <div class="card dashboard-bootstrap-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="section-label mb-1">Recent Activity</p>
                                <h3 class="chart-title mb-0">Transactions</h3>
                            </div>
                        </div>

                        <div class="inventory-tracker-list">
                            @foreach ($orders->take(5) as $order)
                                @php
                                    list($product, $quantity) = explode(' x', $order['items']);
                                @endphp
                                <div class="inventory-tracker-row">
                                    <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                        <div style="flex: 1;">
                                            <div class="inventory-item-title inventory-item-title-sm">{{ trim($product) }}</div>
                                            <div class="table-meta">Qty: {{ trim($quantity) }} to {{ $order['customer'] }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">{{ $order['date'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                            @if (count($orders) === 0)
                                <p class="text-muted mb-0">No transactions yet.</p>
                            @endif
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
            gap: 0.5rem;
            margin-top: auto;
        }

        .product-actions .btn {
            flex: 1;
            font-size: 0.85rem;
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
    </style>

    <script>
        // Quick Sale Control Functionality
        let currentPrice = 0;

        function increasePrice() {
            try {
                currentPrice += 100;
                updatePrice();
            } catch (error) {
                console.error('Error in increasePrice:', error);
            }
        }

        function decreasePrice() {
            try {
                currentPrice = Math.max(0, currentPrice - 100);
                updatePrice();
            } catch (error) {
                console.error('Error in decreasePrice:', error);
            }
        }

        function updatePrice() {
            const priceInput = document.getElementById('priceInput');
            if (priceInput) {
                priceInput.value = (currentPrice / 100).toFixed(2);
                calculateTotal();
            }
        }

        function calculateTotal() {
            const priceInput = document.getElementById('priceInput');
            const quantityInput = document.getElementById('quantityInput');
            const totalAmount = document.getElementById('totalAmount');

            if (!totalAmount) return;

            const price = priceInput ? (parseFloat(priceInput.value) || 0) : 0;
            const quantity = quantityInput ? (parseInt(quantityInput.value) || 0) : 0;
            const total = price * quantity;
            totalAmount.textContent = total.toFixed(2);
        }

        function selectRaiser() {
            alert('Raiser selection functionality coming soon...');
        }

        function selectCustomer() {
            alert('Customer selection functionality coming soon...');
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantityInput');
            const kiloInput = document.getElementById('kiloInput');

            if (quantityInput) {
                quantityInput.addEventListener('input', calculateTotal);
            }

            if (kiloInput) {
                kiloInput.addEventListener('input', calculateTotal);
            }

            // Initialize price display
            updatePrice();
        });
    </script>
@endsection
