@extends('layouts.cashier')

@php
    $hideTopbarTitle = true;
@endphp

@section('content')
    <section class="retail-page">
        @if (session('status'))
            <div class="alert alert-success mb-4">{{ session('status') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="page-title mb-0">Archived Products</h1>
            <a href="{{ route('cashier.inventory') }}" class="btn btn-dark">
                <i class="bi bi-arrow-left"></i> Back to Inventory
            </a>
        </div>

        @if ($archivedProducts->count() > 0)
            <div class="row g-4">
                @foreach ($archivedProducts as $product)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="product-card archived-card">
                            <div class="product-image-wrapper">
                                @if ($product['image'])
                                    <img src="{{ Storage::url($product['image']) }}" alt="{{ $product['name'] }}" class="product-image">
                                @else
                                    <i class="bi bi-box"></i>
                                @endif
                                <div class="archived-badge">
                                    <i class="bi bi-archive"></i> Archived
                                </div>
                            </div>

                            <div class="product-card-body">
                                <div class="product-header">
                                    <h5 class="product-name">{{ $product['name'] }}</h5>
                                    <span class="category-badge">{{ $product['category'] }}</span>
                                </div>

                                <p class="product-description">{{ \Illuminate\Support\Str::limit($product['description'] ?: 'No description available.', 80) }}</p>

                                <div class="detail-row">
                                    <span class="detail-label">Price:</span>
                                    <span class="detail-value">{{ $product['price'] }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Stock:</span>
                                    <span class="detail-value">{{ $product['stock'] }} units</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Archived:</span>
                                    <span class="detail-value">{{ $product['archivedAt'] }}</span>
                                </div>

                                <form method="POST" action="{{ route('cashier.retail.products.restore', $product['id']) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Restore this product?')">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-page">
                <div class="empty-icon"><i class="bi bi-archive"></i></div>
                <h3>No Archived Products</h3>
                <p class="text-muted mb-3">All products are currently active.</p>
                <a href="{{ route('cashier.inventory') }}" class="btn btn-dark">Back to Inventory</a>
            </div>
        @endif
    </section>

    <style>
        .product-card {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 0.75rem;
            overflow: hidden;
            height: 100%;
        }

        .archived-card {
            position: relative;
        }

        .product-image-wrapper {
            position: relative;
            height: 180px;
            background: var(--pt-surface-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--pt-muted);
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-image-wrapper i {
            font-size: 3rem;
        }

        .archived-badge {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: var(--pt-surface-soft);
            color: var(--pt-text);
            border: 1px solid var(--pt-border);
            border-radius: 0.4rem;
            padding: 0.3rem 0.55rem;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .archived-badge i {
            font-size: 0.8rem !important;
            line-height: 1;
            width: 0.9rem;
            height: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .product-card-body {
            padding: 0.9rem;
            color: var(--pt-text);
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 0.55rem;
        }

        .product-name {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: var(--pt-text);
        }

        .category-badge {
            background: var(--pt-surface-soft);
            color: var(--pt-muted);
            border: 1px solid var(--pt-border);
            border-radius: 0.35rem;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0.25rem 0.55rem;
            white-space: nowrap;
        }

        .product-description {
            margin: 0 0 0.7rem 0;
            font-size: 0.85rem;
            color: var(--pt-muted);
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            gap: 0.6rem;
            border-bottom: 1px solid var(--pt-border);
            padding-bottom: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .detail-label {
            color: var(--pt-muted);
        }

        .detail-value {
            color: var(--pt-text);
            font-weight: 700;
        }

        :root[data-theme="dark"] .product-card,
        :root[data-theme="dark"] .product-card-body,
        :root[data-theme="dark"] .product-name,
        :root[data-theme="dark"] .detail-value {
            color: #eaf1ff;
        }

        :root[data-theme="dark"] .product-description,
        :root[data-theme="dark"] .detail-label {
            color: #b8c8e0;
        }

        .empty-page {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--pt-muted);
            margin-bottom: 1rem;
            opacity: 0.6;
        }
    </style>
@endsection
