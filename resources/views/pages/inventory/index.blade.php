@extends('layouts.admin')

@php
    $hideTopbarTitle = true;
@endphp

@section('content')
    <style>
        .inventory-page {
            padding: 1.5rem 0;
        }

        .inventory-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .inventory-search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
            display: flex;
            align-items: center;
        }

        .inventory-search-box input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border: 1px solid;
            border-radius: 0.375rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        /* Light mode */
        .inventory-search-box input {
            background-color: #f3f4f6;
            border-color: #e5e7eb;
            color: #1f2937;
        }

        .inventory-search-box input::placeholder {
            color: #9ca3af;
        }

        .inventory-search-box input:focus {
            outline: none;
            background-color: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Dark mode */
        :root[data-theme="dark"] .inventory-search-box input {
            background-color: #1f2937;
            border-color: #374151;
            color: #f3f4f6;
        }

        :root[data-theme="dark"] .inventory-search-box input::placeholder {
            color: #6b7280;
        }

        :root[data-theme="dark"] .inventory-search-box input:focus {
            background-color: #111827;
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
        }

        .inventory-search-icon {
            position: absolute;
            left: 0.75rem;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
        }

        :root[data-theme="dark"] .inventory-search-icon {
            color: #6b7280;
        }

        .inventory-search-btn {
            padding: 0.6rem 1.5rem;
            margin-left: 0.5rem;
            border: none;
            border-radius: 0.375rem;
            background-color: #000000;
            color: #ffffff;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .inventory-search-btn:hover {
            background-color: #1f2937;
        }

        :root[data-theme="dark"] .inventory-search-btn {
            background-color: #ffffff;
            color: #000000;
        }

        :root[data-theme="dark"] .inventory-search-btn:hover {
            background-color: #e5e7eb;
        }

        /* Elevated Card Container */
        .inventory-card {
            background: transparent;
            border: 1px solid;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .inventory-card {
            border-color: #e5e7eb;
            background-color: #ffffff;
        }

        :root[data-theme="dark"] .inventory-card {
            border-color: #2d3748;
            background-color: #111827;
        }

        .inventory-card-body {
            padding: 0;
        }
        }

        .inventory-search-btn:hover {
            background-color: #1f2937;
        }

        :root[data-theme="dark"] .inventory-search-btn {
            background-color: #ffffff;
            color: #000000;
        }

        :root[data-theme="dark"] .inventory-search-btn:hover {
            background-color: #e5e7eb;
        }

        .add-item-btn {
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: #ffffff;
            color: #0f172a;
            text-decoration: none;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }

        .add-item-btn:hover {
            background-color: #f8fafc;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        :root[data-theme="dark"] .add-item-btn {
            background-color: #ffffff;
            color: #000000;
            border-color: #ffffff;
        }

        :root[data-theme="dark"] .add-item-btn:hover {
            background-color: #e5e7eb;
            color: #000000;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
        }

        .inventory-table-container {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .inventory-table thead {
            background-color: #0f172a;
            border-bottom: 1px solid #e5e7eb;
        }

        :root[data-theme="dark"] .inventory-table thead {
            background-color: #0f172a;
            border-bottom: 1px solid #1e293b;
        }

        .inventory-table thead th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
        }

        :root[data-theme="dark"] .inventory-table thead th {
            color: #94a3b8;
        }

        .inventory-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 0.2s ease;
        }

        :root[data-theme="dark"] .inventory-table tbody tr {
            border-bottom: 1px solid #1e293b;
        }

        .inventory-table tbody tr:hover {
            background-color: #f8fafc;
        }

        :root[data-theme="dark"] .inventory-table tbody tr:hover {
            background-color: #1e293b;
        }

        .inventory-table tbody td {
            padding: 1.25rem 1.5rem;
            color: #1f2937;
            font-size: 0.9rem;
        }

        :root[data-theme="dark"] .inventory-table tbody td {
            color: #e2e8f0;
        }

        .inventory-row-number {
            color: #94a3b8;
            font-weight: 600;
        }

        :root[data-theme="dark"] .inventory-row-number {
            color: #64748b;
        }

        .item-name {
            font-weight: 700;
            color: #0f172a;
        }

        :root[data-theme="dark"] .item-name {
            color: #f1f5f9;
        }

        .category-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background-color: #f1f5f9;
            color: #334155;
        }

        :root[data-theme="dark"] .category-badge {
            background-color: #1e293b;
            color: #cbd5e0;
        }

        .stock-badge {
            display: inline-block;
            padding: 0.4rem 0.85rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .stock-badge.in-stock {
            background-color: #d1fae5;
            color: #065f46;
        }

        .stock-badge.low-stock {
            background-color: #fef3c7;
            color: #854d0e;
        }

        .stock-badge.restock {
            background-color: #fee2e2;
            color: #991b1b;
        }

        :root[data-theme="dark"] .stock-badge.in-stock {
            background-color: #10b981;
            color: #d1fae5;
        }

        :root[data-theme="dark"] .stock-badge.low-stock {
            background-color: #f59e0b;
            color: #fef3c7;
        }

        :root[data-theme="dark"] .stock-badge.restock {
            background-color: #ef4444;
            color: #fee2e2;
        }

        .stock-entry-btn {
            padding: 0.45rem 1.1rem;
            border: none;
            border-radius: 0.35rem;
            background-color: #10b981;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: capitalize;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .stock-entry-btn:hover {
            background-color: #059669;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
        }

        :root[data-theme="dark"] .stock-entry-btn {
            background-color: #10b981;
        }

        :root[data-theme="dark"] .stock-entry-btn:hover {
            background-color: #059669;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #64748b;
            font-size: 0.95rem;
        }

        :root[data-theme="dark"] .empty-state {
            color: #94a3b8;
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

        .retail-category-section {
            margin-bottom: 1.5rem;
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
            border-bottom: none;
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

        .price-selector-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--pt-muted);
            margin-bottom: 0.35rem;
        }

        .price-selector {
            width: 100%;
            border: 1px solid var(--pt-border);
            border-radius: 0.45rem;
            background: var(--pt-surface-soft);
            color: var(--pt-text);
            min-height: 38px;
            padding: 0.35rem 0.55rem;
            margin-bottom: 0.55rem;
            font-size: 0.82rem;
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

        .product-actions > a,
        .product-actions > form {
            flex: 1 1 0;
            min-width: 0;
        }

        .product-actions form {
            display: flex;
        }

        .btn-product-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            min-height: 38px;
            padding: 0.42rem 0.55rem;
            border: 1px solid var(--pt-border);
            border-radius: 0.45rem;
            text-decoration: none;
            color: var(--pt-text);
            background: var(--pt-surface-soft);
            font-weight: 600;
            font-size: 0.82rem;
            width: 100%;
            text-align: center;
            white-space: nowrap;
        }
    </style>

    <h1 class="page-title mb-5">Inventory</h1>
    <section class="inventory-page">
        <div class="transfer-header">
            <div class="transfer-actions">
                <a href="{{ route('retail.archives') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-archive"></i> Archives
                </a>
                <a href="{{ route('retail.products.create') }}" class="btn btn-dark">
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

            $productsByCategoryTransfer = [];
            foreach (($catalog ?? []) as $item) {
                $cat = $item['category'] ?? 'Feeds';
                if (!isset($productsByCategoryTransfer[$cat])) {
                    $productsByCategoryTransfer[$cat] = [];
                }
                $productsByCategoryTransfer[$cat][] = $item;
            }
        @endphp

        @foreach ($categoriesTransfer as $categoryName => $categoryKey)
            @php
                $products = $productsByCategoryTransfer[$categoryKey] ?? [];
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
                                            <div class="price-value" id="price_{{ $product['id'] }}">P {{ number_format($product['rawPrice'], 2) }}</div>
                                        </div>

                                        <div class="detail-row">
                                            <span class="detail-label">Sold:</span>
                                            <span class="detail-value">{{ $product['sales'] }}</span>
                                        </div>

                                        <div class="product-actions">
                                            <a href="{{ route('retail.products.edit', $product['id']) }}" class="btn-product-action">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form method="POST" action="{{ route('retail.products.destroy', $product['id']) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-product-action" onclick="return confirm('Archive this product?')">
                                                    <i class="bi bi-archive"></i> Archive
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state py-3 text-start">
                        No available products.
                    </div>
                @endif
            </div>
        @endforeach
    </section>

    <!-- Stock Entry Modal -->
    <div id="stockEntryModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 1050; align-items: center; justify-content: center;">
        <style>
            #stockEntryModal {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .stock-modal-content {
                background: white;
                border-radius: 0.5rem;
                max-width: 500px;
                width: 90%;
                padding: 2rem;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            }

            :root[data-theme="dark"] .stock-modal-content {
                background: #111827;
                color: #f3f4f6;
            }

            .stock-modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .stock-modal-title {
                font-size: 1.5rem;
                font-weight: 700;
                margin: 0;
                color: #0f172a;
            }

            :root[data-theme="dark"] .stock-modal-title {
                color: #f1f5f9;
            }

            .stock-modal-close {
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
                color: #6b7280;
                transition: color 0.2s;
            }

            .stock-modal-close:hover {
                color: #1f2937;
            }

            :root[data-theme="dark"] .stock-modal-close:hover {
                color: #e5e7eb;
            }

            .stock-modal-product-info {
                background-color: #f3f4f6;
                padding: 1rem;
                border-radius: 0.375rem;
                margin-bottom: 1.5rem;
            }

            :root[data-theme="dark"] .stock-modal-product-info {
                background-color: #1f2937;
            }

            .stock-modal-label {
                font-size: 0.85rem;
                color: #6b7280;
                margin: 0 0 0.25rem 0;
            }

            :root[data-theme="dark"] .stock-modal-label {
                color: #9ca3af;
            }

            .stock-modal-product-name {
                font-weight: 700;
                font-size: 1.1rem;
                margin: 0;
                color: #0f172a;
            }

            :root[data-theme="dark"] .stock-modal-product-name {
                color: #f1f5f9;
            }

            .stock-form {
                display: flex;
                flex-direction: column;
                gap: 1.25rem;
            }

            .stock-form-group {
                display: flex;
                flex-direction: column;
            }

            .stock-form-label {
                display: block;
                font-weight: 600;
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
                color: #1f2937;
            }

            :root[data-theme="dark"] .stock-form-label {
                color: #e5e7eb;
            }

            .stock-form-input {
                padding: 0.6rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.375rem;
                font-size: 0.9rem;
                transition: all 0.3s ease;
                background-color: #ffffff;
                color: #1f2937;
            }

            :root[data-theme="dark"] .stock-form-input {
                background-color: #1f2937;
                border-color: #374151;
                color: #f3f4f6;
            }

            .stock-form-input::placeholder {
                color: #9ca3af;
            }

            :root[data-theme="dark"] .stock-form-input::placeholder {
                color: #6b7280;
            }

            .stock-form-input:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            :root[data-theme="dark"] .stock-form-input:focus {
                border-color: #60a5fa;
                box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
            }

            .stock-form-textarea {
                padding: 0.6rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.375rem;
                font-size: 0.9rem;
                resize: vertical;
                min-height: 80px;
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: #ffffff;
                color: #1f2937;
                transition: all 0.3s ease;
            }

            :root[data-theme="dark"] .stock-form-textarea {
                background-color: #1f2937;
                border-color: #374151;
                color: #f3f4f6;
            }

            .stock-form-textarea::placeholder {
                color: #9ca3af;
            }

            :root[data-theme="dark"] .stock-form-textarea::placeholder {
                color: #6b7280;
            }

            .stock-form-textarea:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            :root[data-theme="dark"] .stock-form-textarea:focus {
                border-color: #60a5fa;
                box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
            }

            .stock-form-buttons {
                display: flex;
                gap: 1rem;
                margin-top: 1rem;
            }

            .stock-btn {
                flex: 1;
                padding: 0.6rem;
                border: none;
                border-radius: 0.375rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .stock-btn-submit {
                background-color: #10b981;
                color: white;
            }

            .stock-btn-submit:hover {
                background-color: #059669;
                box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
            }

            .stock-btn-cancel {
                background-color: #e5e7eb;
                color: #1f2937;
                border: 1px solid #d1d5db;
            }

            .stock-btn-cancel:hover {
                background-color: #d1d5db;
                border-color: #9ca3af;
            }

            :root[data-theme="dark"] .stock-btn-cancel {
                background-color: #374151;
                color: #e5e7eb;
                border: 1px solid #4b5563;
            }

            :root[data-theme="dark"] .stock-btn-cancel:hover {
                background-color: #4b5563;
                border-color: #6b7280;
            }

            .stock-success-message {
                display: none;
                margin-top: 1rem;
                padding: 1rem;
                background-color: #d1fae5;
                border: 1px solid #6ee7b7;
                border-radius: 0.375rem;
                color: #065f46;
            }

            :root[data-theme="dark"] .stock-success-message {
                background-color: #064e3b;
                border-color: #10b981;
                color: #d1fae5;
            }

            .page-title {
                font-size: 2rem;
                font-weight: 700;
                color: var(--pt-text);
                margin-bottom: 1.5rem;
            }

            :root[data-theme="dark"] .page-title {
                color: #ffffff;
            }
        </style>

        <div class="stock-modal-content">
            <div class="stock-modal-header">
                <h2 class="stock-modal-title">Stock Entry</h2>
                <button class="stock-modal-close" onclick="closeStockModal()">×</button>
            </div>

            <div class="stock-modal-product-info">
                <p class="stock-modal-label">Product</p>
                <p class="stock-modal-product-name" id="modalProductName">-</p>
            </div>

            <form id="stockEntryForm" class="stock-form">
                <div class="stock-form-group">
                    <label class="stock-form-label">Movement Type</label>
                    <select id="movementType" name="movement_type" class="stock-form-input" onchange="toggleRaiserField()">
                        <option value="">-- Select Type --</option>
                        <option value="add">Stock In</option>
                        <option value="deduct">Sale</option>
                        <option value="distribute">Distribute to Raiser</option>
                    </select>
                </div>

                <div id="raiserField" style="display: none;" class="stock-form-group">
                    <label class="stock-form-label">Select Raiser</label>
                    <select id="raiserId" name="raiser_id" class="stock-form-input">
                        <option value="">-- Select Raiser --</option>
                        @foreach ($raisers ?? [] as $raiser)
                            <option value="{{ is_array($raiser) ? ($raiser['id'] ?? $loop->index) : $raiser->id }}">
                                {{ is_array($raiser) ? ($raiser['name'] ?? 'Unknown') : $raiser->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="stock-form-group">
                    <label class="stock-form-label">Quantity</label>
                    <input type="number" id="quantity" name="quantity" min="1" class="stock-form-input" required>
                </div>

                <div class="stock-form-buttons">
                    <button type="submit" class="stock-btn stock-btn-submit">Submit</button>
                    <button type="button" class="stock-btn stock-btn-cancel" onclick="closeStockModal()">Cancel</button>
                </div>
            </form>

            <div id="successMessage" class="stock-success-message">
                Stock entry recorded successfully!
            </div>
        </div>
    </div>

    <script>
        let selectedProductId = null;
        let selectedProductName = null;

        function openStockModal(event) {
            event.preventDefault();
            const btn = event.target;
            selectedProductId = btn.getAttribute('data-product-id');
            selectedProductName = btn.getAttribute('data-product-name');
            
            document.getElementById('modalProductName').textContent = selectedProductName;
            document.getElementById('stockEntryForm').reset();
            document.getElementById('successMessage').style.display = 'none';
            document.getElementById('stockEntryModal').style.display = 'flex';
        }

        function closeStockModal() {
            document.getElementById('stockEntryModal').style.display = 'none';
        }

        async function addInventoryToQuickSale(button) {
            const row = button.closest('tr');
            const input = row.querySelector('input[type="number"]');
            const quantity = parseInt(input.value) || 0;
            const itemId = input.getAttribute('data-item-id');
            const itemName = input.getAttribute('data-item-name');
            const itemPrice = input.getAttribute('data-item-price');

            if (quantity < 1) {
                alert('Please enter a quantity');
                input.focus();
                return;
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                const response = await fetch('/api/quick-sale/add-item', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                    },
                    body: JSON.stringify({
                        product_id: itemId,
                        quantity: quantity,
                    }),
                });

                const data = await response.json();
                
                if (data.success) {
                    input.value = '';
                    alert(itemName + ' added to quick sale');
                } else {
                    alert(data.error || 'Error adding to quick sale');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error adding to quick sale: ' + error.message);
            }
        }

        function updatePrice(productId, priceValue) {
            const price = parseFloat(priceValue);
            if (isNaN(price) || price < 0) {
                return;
            }

            const priceDisplay = document.getElementById('price_' + productId);
            if (priceDisplay) {
                priceDisplay.textContent = 'P ' + price.toFixed(2);
            }
        }

        function toggleRaiserField() {
            const movementType = document.getElementById('movementType').value;
            const raiserField = document.getElementById('raiserField');
            raiserField.style.display = movementType === 'distribute' ? 'block' : 'none';
            
            if (movementType === 'distribute') {
                document.getElementById('raiserId').required = true;
            } else {
                document.getElementById('raiserId').required = false;
            }
        }

        document.getElementById('stockEntryForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const movementType = document.getElementById('movementType').value;
            const quantity = document.getElementById('quantity').value;
            const raiserId = document.getElementById('raiserId').value;

            if (!movementType || !quantity) {
                alert('Please fill in all required fields');
                return;
            }

            try {
                const response = await fetch('/api/stock-entry', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        product_id: selectedProductId,
                        movement_type: movementType,
                        quantity: parseInt(quantity),
                        raiser_id: raiserId || null,
                    }),
                });

                if (response.ok) {
                    document.getElementById('successMessage').style.display = 'block';
                    setTimeout(() => {
                        closeStockModal();
                        window.location.reload();
                    }, 1500);
                } else {
                    alert('Error recording stock entry');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error recording stock entry');
            }
        });

        // Close modal when clicking outside
        document.getElementById('stockEntryModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeStockModal();
            }
        });
    </script>
@endsection



