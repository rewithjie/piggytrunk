@extends('layouts.cashier')

@php
    $hideTopbarTitle = true;
@endphp

@section('content')
    <style>
        .inventory-page {
            padding: 1.5rem 0;
        }

        .retail-category-section {
            margin-bottom: 1.5rem;
        }

        .transfer-header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .transfer-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .category-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--pt-text);
            margin: 0;
        }

        .product-card {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 0.75rem;
            height: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .product-image-wrapper {
            width: 100%;
            height: 150px;
            background: var(--pt-surface-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--pt-muted);
        }

        @media (max-width: 575.98px) {
            .product-image-wrapper {
                height: 130px;
            }
        }

        .product-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-image-wrapper i {
            font-size: 3rem;
        }

        .product-card-body {
            padding: 0.8rem;
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.6rem;
        }

        .product-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--pt-muted);
            margin-bottom: 0;
        }

        .product-inline {
            display: flex;
            align-items: baseline;
            gap: 0.25rem;
            margin-bottom: 0.35rem;
        }

        .product-name {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: var(--pt-text);
            line-height: 1.25;
        }

        .stock-badge-transfer {
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.24rem 0.58rem;
            background: var(--pt-surface-soft);
            color: var(--pt-text);
        }

        .product-description {
            margin: 0 0 0.6rem 0;
            color: var(--pt-muted);
            font-size: 0.84rem;
            line-height: 1.35;
        }

        .price-display-container {
            border: 1px solid var(--pt-border);
            background: var(--pt-surface-soft);
            border-radius: 0.65rem;
            padding: 0.55rem 0.7rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.6rem;
        }

        .price-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--pt-muted);
            letter-spacing: 0.06em;
        }

        .price-value {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--pt-text);
            line-height: 1;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid var(--pt-border);
            padding-bottom: 0.6rem;
            margin-bottom: 0.6rem;
            color: var(--pt-muted);
            font-size: 0.82rem;
        }

        .detail-value {
            color: var(--pt-text);
            font-weight: 700;
        }

        .product-actions {
            display: flex;
            gap: 0.5rem;
            align-items: stretch;
        }

        .quick-sale-input-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            width: 100%;
        }

        .quick-sale-input {
            flex: 1;
            min-height: 38px;
            border: 1px solid var(--pt-border);
            border-radius: 0.45rem;
            background: var(--pt-surface-soft);
            color: var(--pt-text);
            padding: 0.42rem 0.55rem;
            font-size: 0.82rem;
        }

        .btn-product-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            min-height: 38px;
            padding: 0.42rem 0.75rem;
            border: 1px solid var(--pt-border);
            border-radius: 0.45rem;
            text-decoration: none;
            color: var(--pt-text);
            background: var(--pt-surface-soft);
            font-weight: 600;
            font-size: 0.82rem;
            text-align: center;
            white-space: nowrap;
        }

        .empty-state {
            text-align: left;
            padding: 0.75rem 0.25rem;
            color: var(--pt-muted);
        }
    </style>

    <h1 class="page-title mb-5">Inventory</h1>
    <section class="inventory-page">
        <div class="transfer-header">
            <div class="transfer-actions">
                <a href="{{ route('cashier.retail.archives') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-archive"></i> Archives
                </a>
                <a href="{{ route('cashier.retail.products.create') }}" class="btn btn-dark">
                    <i class="bi bi-plus-lg"></i> Add Product
                </a>
            </div>
        </div>

        @php
            $categoriesTransfer = [
                'Feeds' => 'Feeds',
                'Vitamins' => 'Vitamins',
                'Medicines' => 'Medicines',
                'Others' => 'Others',
            ];
        @endphp

        @foreach ($categoriesTransfer as $categoryName => $categoryKey)
            @php
                $products = $productsByCategory[$categoryKey] ?? collect();
            @endphp

            <div class="retail-category-section">
                <div class="category-header mb-3">
                    <h3 class="category-title">{{ $categoryName }}</h3>
                </div>

                @if (count($products) > 0)
                    <div class="row g-3 mb-3">
                        @foreach ($products as $product)
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="product-card">
                                    @if ($product['image'])
                                        <div class="product-image-wrapper">
                                            <img src="{{ Storage::url($product['image']) }}" alt="{{ $product['name'] }}">
                                        </div>
                                    @else
                                        <div class="product-image-wrapper">
                                            <i class="bi bi-box"></i>
                                        </div>
                                    @endif

                                    <div class="product-card-body">
                                        <div class="product-header">
                                            <div class="product-inline">
                                                <span class="product-label">Product Name:</span>
                                                <h5 class="product-name">{{ $product['name'] }}</h5>
                                            </div>
                                            <span class="stock-badge-transfer">{{ $product['stock'] }} units</span>
                                        </div>

                                        <div class="product-inline">
                                            <span class="product-label">Description:</span>
                                            <p class="product-description">{{ \Illuminate\Support\Str::limit($product['description'] ?: 'No description available.', 80) }}</p>
                                        </div>

                                        <div class="price-display-container">
                                            <div class="price-label">Price:</div>
                                            <div class="price-value">P {{ number_format($product['rawPrice'], 2) }}</div>
                                        </div>

                                        <div class="detail-row">
                                            <span class="detail-label">Sold:</span>
                                            <span class="detail-value">{{ $product['sales'] }} units</span>
                                        </div>

                                        <div class="product-actions">
                                            <div class="quick-sale-input-group">
                                                <input type="number" class="quick-sale-input" data-product-id="{{ $product['id'] }}" data-product-name="{{ $product['name'] }}" placeholder="Qty" min="1">
                                                <button type="button" class="btn-product-action" onclick="addInventoryToQuickSale(this)">
                                                    <i class="bi bi-cart-plus"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">No available products.</div>
                @endif
            </div>
        @endforeach
    </section>

    <script>
        async function addInventoryToQuickSale(button) {
            const group = button.closest('.quick-sale-input-group');
            const input = group.querySelector('.quick-sale-input');
            const quantity = parseInt(input.value, 10) || 0;
            const productId = input.getAttribute('data-product-id');
            const productName = input.getAttribute('data-product-name');

            if (quantity < 1) {
                alert('Please enter a quantity');
                input.focus();
                return;
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                const response = await fetch('/api/cashier/quick-sale/add-item', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity,
                    }),
                });

                const data = await response.json();
                if (data.success) {
                    input.value = '';
                    alert(productName + ' added to quick sale');
                } else {
                    alert(data.error || 'Error adding to quick sale');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error adding to quick sale');
            }
        }
    </script>
@endsection
