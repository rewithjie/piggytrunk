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
                <h1 class="page-title mb-0">Archived Products</h1>
            </div>
            <a href="{{ route('retail.index') }}" class="btn btn-dark">
                <i class="bi bi-arrow-left"></i> Back to Retail
            </a>
        </div>

        <!-- Archived Products Grid -->
        @if ($archivedProducts->count() > 0)
            <div class="archived-products-grid">
                <div class="row g-4">
                    @foreach ($archivedProducts as $product)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="product-card archived-card">
                                <!-- Product Image -->
                                @if ($product['image'])
                                    <div class="product-image-wrapper">
                                        <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" class="product-image">
                                        <div class="archived-badge">
                                            <i class="bi bi-archive"></i> Archived
                                        </div>
                                    </div>
                                @else
                                    <div class="product-image-wrapper product-image-placeholder">
                                        <i class="bi bi-box"></i>
                                        <div class="archived-badge">
                                            <i class="bi bi-archive"></i> Archived
                                        </div>
                                    </div>
                                @endif

                                <div class="product-card-body">
                                    <div class="product-header">
                                        <h5 class="product-name">{{ $product['name'] }}</h5>
                                        <span class="category-badge">{{ $product['category'] }}</span>
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
                                            <span class="detail-label">Stock:</span>
                                            <span class="detail-value">{{ $product['stock'] }} units</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Archived:</span>
                                            <span class="detail-value">{{ $product['archivedAt'] }}</span>
                                        </div>
                                    </div>

                                    <div class="product-actions mt-3">
                                        <form method="POST" action="{{ route('retail.products.restore', $product['id']) }}" style="display: flex;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success w-100" onclick="return confirm('Restore this product?')">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="empty-page">
                <div class="empty-icon">
                    <i class="bi bi-archive"></i>
                </div>
                <h3>No Archived Products</h3>
                <p class="text-muted mb-3">All your products are currently active</p>
                <a href="{{ route('retail.index') }}" class="btn btn-dark">Back to Retail Shop</a>
            </div>
        @endif
    </section>

    <style>
        .archived-card {
            opacity: 0.85;
            position: relative;
        }

        .archived-badge {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: rgba(255, 107, 107, 0.95);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            backdrop-filter: blur(4px);
        }

        .category-badge {
            display: inline-block;
            background: var(--pt-surface-soft);
            color: var(--pt-muted);
            padding: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .product-actions.mt-3 {
            margin-top: 1rem !important;
            padding-top: 1rem;
            border-top: 1px solid var(--pt-border);
        }

        /* Dark mode fixes */
        :root[data-theme="dark"] .archived-badge {
            background: rgba(255, 107, 107, 0.95);
            color: white;
        }

        :root[data-theme="dark"] .category-badge {
            background: var(--pt-surface-soft);
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
            opacity: 0.5;
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
    </style>
@endsection
