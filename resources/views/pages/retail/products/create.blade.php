@extends('layouts.admin')

@php
    $hideTopbarTitle = true;
@endphp

@section('content')
    <section class="bootstrap-dashboard">
        <div class="dashboard-stage">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="card dashboard-bootstrap-card">
                        <div class="card-body p-5">
                            <h1 class="page-title mb-5">Add New Product</h1>

                            @if ($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <strong>Please fix the following errors:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('retail.products.store') }}" enctype="multipart/form-data" class="product-form">
                @csrf

                <div class="form-container">
                    <div class="row">
                        <!-- Image Upload Section (Left Column) -->
                        <div class="col-12 col-md-4">
                            <div class="form-section">
                                <label class="form-label-main">Product Photo</label>
                                <div class="image-upload-wrapper">
                                    <input type="file" id="imageInput" name="image" class="image-input" accept="image/*">
                                    <div class="image-upload-area" onclick="document.getElementById('imageInput').click()">
                                        <div class="upload-icon">
                                            <i class="bi bi-cloud-arrow-up"></i>
                                        </div>
                                        <div class="upload-text">
                                            <p class="upload-main-text">Click to upload</p>
                                            <p class="upload-sub-text">PNG, JPG, WebP</p>
                                        </div>
                                    </div>
                                    <div class="image-preview-wrapper" id="imagePreviewWrapper" style="display: none;">
                                        <img id="imagePreview" class="image-preview" alt="Preview">
                                        <button type="button" class="btn-remove-image" onclick="removeImage(event)">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Fields (Right Column) -->
                        <div class="col-12 col-md-8">
                            <!-- Row 1: Product Name & Category -->
                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Product Name *</label>
                                        <input 
                                            type="text" 
                                            name="name" 
                                            class="form-control @error('name') is-invalid @enderror" 
                                            value="{{ old('name') }}" 
                                            placeholder="e.g., Premium Hog Feed"
                                            required
                                        >
                                        @error('name')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Row 2: Category & Price & Stock -->
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Category *</label>
                                        <div class="custom-select-wrapper">
                                            <select name="category" class="form-select custom-select @error('category') is-invalid @enderror" required>
                                                <option value="">Select category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </div>
                                        @error('category')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Stock *</label>
                                        <input 
                                            type="number" 
                                            min="0" 
                                            name="stock" 
                                            class="form-control @error('stock') is-invalid @enderror" 
                                            placeholder="0"
                                            required
                                        >
                                        @error('stock')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Row 3: Pricing Tiers Header -->
                            <div class="row g-3 mb-2">
                                <div class="col-12">
                                    <label class="form-label" style="margin-bottom: 0.5rem;">Pricing Tiers</label>
                                </div>
                            </div>

                            <!-- FEEDS PRICING (shown by default or when Feeds selected) -->
                            <div id="feeds-pricing" class="pricing-section" style="display: none;">
                                <!-- Row 4: Price Per Sack -->
                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Price Per Sack (₱)</label>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                name="price_per_sack" 
                                                class="form-control @error('price_per_sack') is-invalid @enderror" 
                                                value="{{ old('price_per_sack') }}"
                                                placeholder="0.00"
                                            >
                                            @error('price_per_sack')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Price Per 1 Kilo (₱) *</label>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                name="price_per_kilo" 
                                                class="form-control feeds-required @error('price_per_kilo') is-invalid @enderror" 
                                                value="{{ old('price_per_kilo') }}"
                                                placeholder="0.00"
                                            >
                                            @error('price_per_kilo')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 5: Price Per 1/2 and 1/4 Kilo -->
                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Price Per 1/2 Kilo (₱)</label>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                name="price_per_half_kilo" 
                                                class="form-control @error('price_per_half_kilo') is-invalid @enderror" 
                                                value="{{ old('price_per_half_kilo') }}"
                                                placeholder="0.00"
                                            >
                                            @error('price_per_half_kilo')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Price Per 1/4 Kilo (₱)</label>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                name="price_per_quarter_kilo" 
                                                class="form-control @error('price_per_quarter_kilo') is-invalid @enderror" 
                                                value="{{ old('price_per_quarter_kilo') }}"
                                                placeholder="0.00"
                                            >
                                            @error('price_per_quarter_kilo')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- VITAMINS PRICING -->
                            <div id="vitamins-pricing" class="pricing-section" style="display: none;">
                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Price Per Bottle (₱) *</label>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                name="price_per_bottle" 
                                                class="form-control vitamins-required @error('price_per_bottle') is-invalid @enderror" 
                                                value="{{ old('price_per_bottle') }}"
                                                placeholder="0.00"
                                            >
                                            @error('price_per_bottle')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Price Per Tablet (₱)</label>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                name="price_per_tablet" 
                                                class="form-control @error('price_per_tablet') is-invalid @enderror" 
                                                value="{{ old('price_per_tablet') }}"
                                                placeholder="0.00"
                                            >
                                            @error('price_per_tablet')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Price Per Vial (₱)</label>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                name="price_per_vial" 
                                                class="form-control @error('price_per_vial') is-invalid @enderror" 
                                                value="{{ old('price_per_vial') }}"
                                                placeholder="0.00"
                                            >
                                            @error('price_per_vial')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MEDICINES PRICING -->
                            <div id="medicines-pricing" class="pricing-section" style="display: none;">
                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Price Per Bottle (₱) *</label>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                name="price_per_bottle" 
                                                class="form-control medicines-required @error('price_per_bottle') is-invalid @enderror" 
                                                value="{{ old('price_per_bottle') }}"
                                                placeholder="0.00"
                                            >
                                            @error('price_per_bottle')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Price Per Tablet (₱)</label>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                name="price_per_tablet" 
                                                class="form-control @error('price_per_tablet') is-invalid @enderror" 
                                                value="{{ old('price_per_tablet') }}"
                                                placeholder="0.00"
                                            >
                                            @error('price_per_tablet')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Price Per Injection (₱)</label>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                name="price_per_injection" 
                                                class="form-control @error('price_per_injection') is-invalid @enderror" 
                                                value="{{ old('price_per_injection') }}"
                                                placeholder="0.00"
                                            >
                                            @error('price_per_injection')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- GROWTH ADDITIVES PRICING -->
                            <div id="additives-pricing" class="pricing-section" style="display: none;">
                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Price Per Liter (₱) *</label>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                name="price_per_liter" 
                                                class="form-control additives-required @error('price_per_liter') is-invalid @enderror" 
                                                value="{{ old('price_per_liter') }}"
                                                placeholder="0.00"
                                            >
                                            @error('price_per_liter')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Price Per Bottle (₱)</label>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                name="price_per_bottle" 
                                                class="form-control @error('price_per_bottle') is-invalid @enderror" 
                                                value="{{ old('price_per_bottle') }}"
                                                placeholder="0.00"
                                            >
                                            @error('price_per_bottle')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Price Per Sachet (₱)</label>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                name="price_per_sachet" 
                                                class="form-control @error('price_per_sachet') is-invalid @enderror" 
                                                value="{{ old('price_per_sachet') }}"
                                                placeholder="0.00"
                                            >
                                            @error('price_per_sachet')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 6: Description -->
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea 
                                            name="description" 
                                            class="form-control form-textarea @error('description') is-invalid @enderror"
                                            placeholder="Add product details, usage instructions, or benefits..."
                                            rows="3"
                                        >{{ old('description') }}</textarea>
                                        <div class="character-count">
                                            <span id="charCount">0</span>/1000
                                        </div>
                                        @error('description')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Add Product
                                </button>
                                <a href="{{ route('retail.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-lg me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .section-heading {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--pt-text);
        }

        .product-form .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--pt-muted);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }

        .product-form .form-control,
        .product-form .form-select {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            color: var(--pt-text);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .product-form .form-select {
            padding-right: 2.5rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23666666' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        .product-form .form-control::placeholder {
            color: var(--pt-muted);
        }

        .product-form .form-control:focus,
        .product-form .form-select:focus {
            background: var(--pt-surface);
            border-color: #5b8def;
            color: var(--pt-text);
            box-shadow: 0 0 0 3px rgba(91, 141, 239, 0.1);
        }

        .product-form .input-group-text {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            color: var(--pt-muted);
            font-weight: 600;
        }

        .btn-primary {
            background: #1a1a1a;
            border: 1.5px solid transparent;
            color: #ffffff;
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            background: #2d2d2d;
            color: #ffffff;
            border-color: #ffffff;
        }

        :root[data-theme="dark"] .btn-primary {
            background: #ffffff;
            border: 1.5px solid transparent;
            color: #1a1a1a;
            box-shadow: 0 2px 8px rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        :root[data-theme="dark"] .btn-primary:hover {
            background: #f5f5f5;
            color: #1a1a1a;
            border-color: #1a1a1a;
        }

        .btn-secondary {
            background: transparent;
            border: 1.5px solid var(--pt-text);
            color: var(--pt-text);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: var(--pt-surface-soft);
            color: var(--pt-text);
            border-color: var(--pt-text);
        }

        :root[data-theme="dark"] .btn-secondary {
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.6);
            color: var(--pt-text);
            transition: all 0.3s ease;
        }

        :root[data-theme="dark"] .btn-secondary:hover {
            background: var(--pt-surface-soft);
            border-color: #ffffff;
            color: var(--pt-text);
        }

        .text-danger {
            color: var(--pt-accent);
        }

        :root[data-theme="dark"] .form-control,
        :root[data-theme="dark"] .form-select {
            background-color: var(--pt-surface-soft) !important;
            border-color: var(--pt-border) !important;
            color: var(--pt-text) !important;
        }

        :root[data-theme="dark"] .form-select {
            background-color: var(--pt-surface-soft) !important;
            border-color: var(--pt-border) !important;
            color: var(--pt-text) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%239cb0c9' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 16px 12px !important;
            padding-right: 2.5rem !important;
        }

        :root[data-theme="dark"] .form-control::placeholder {
            color: var(--pt-muted);
        }

        :root[data-theme="dark"] .form-control:focus,
        :root[data-theme="dark"] .form-select:focus {
            background-color: var(--pt-surface) !important;
            border-color: #5b8def !important;
            color: var(--pt-text) !important;
        }

        :root[data-theme="dark"] .input-group-text {
            background-color: transparent !important;
            border-color: transparent !important;
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .product-form .input-group {
            border-color: var(--pt-border) !important;
        }

        :root[data-theme="dark"] .product-form .input-group:focus-within {
            border-color: #5b8def !important;
            box-shadow: 0 0 0 0.2rem rgba(91, 141, 239, 0.15) !important;
        }

        /* Remove number spinner arrows */
        .product-form .no-spinner::-webkit-outer-spin-button,
        .product-form .no-spinner::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
            display: none;
        }

        .product-form .no-spinner[type=number] {
            -moz-appearance: textfield;
        }

        /* Input group styling */
        .product-form .input-group {
            border: 1px solid var(--pt-border);
            border-radius: 0.375rem;
            overflow: hidden;
            transition: all 0.2s ease;
            display: flex;
            align-items: stretch;
        }

        .product-form .input-group:focus-within {
            border-color: #5b8def;
            box-shadow: 0 0 0 0.2rem rgba(91, 141, 239, 0.15);
        }

        .product-form .input-group-text {
            background-color: transparent;
            border: none;
            font-weight: 600;
            padding: 0.75rem 0.75rem;
            color: var(--pt-text);
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 50px;
        }

        .product-form .input-group .form-control {
            border: none;
            padding-left: 0.5rem;
        }

        .product-form .input-group .form-control:focus {
            border: none;
            box-shadow: none;
            outline: none;
        }

        :root[data-theme="dark"] .product-form .input-group-text {
            color: var(--pt-text);
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem !important;
            }

            .section-heading {
                font-size: 1.25rem;
            }
        }

        /* Product-specific styles */
        .image-upload-area {
            border: 2px dashed var(--pt-border);
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--pt-surface-soft);
        }

        .image-upload-area:hover {
            border-color: var(--pt-primary);
            background: color-mix(in srgb, var(--pt-primary) 5%, var(--pt-surface-soft));
        }

        .upload-icon {
            font-size: 2.5rem;
            color: var(--pt-primary);
            margin-bottom: 1rem;
        }

        .upload-main-text {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--pt-text);
            margin: 0;
        }

        .upload-sub-text {
            font-size: 0.85rem;
            color: var(--pt-muted);
            margin: 0.25rem 0 0 0;
        }

        .image-preview-wrapper {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 1rem;
            max-width: 300px;
        }

        .image-preview {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 8px;
        }

        .btn-remove-image {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(0, 0, 0, 0.6);
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            transition: background 0.2s ease;
        }

        .btn-remove-image:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-section {
            display: flex;
            flex-direction: column;
        }

        .form-section-full {
            width: 100%;
        }

        .form-label-main {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--pt-text);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .row > [class*="col-"] {
                margin-bottom: 1rem;
            }
        }
            margin-bottom: 1rem;
            display: block;
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .character-count {
            font-size: 0.8rem;
            color: var(--pt-muted);
            margin-top: 0.5rem;
            text-align: right;
        }

        .error-message {
            font-size: 0.85rem;
            color: #dc3545;
            margin-top: 0.25rem;
            display: block;
        }

        .alert {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
        }

        :root[data-theme="dark"] .alert {
            background-color: color-mix(in srgb, #dc3545 15%, var(--pt-surface));
            border-color: color-mix(in srgb, #dc3545 30%, var(--pt-border));
            color: #ff6b7a;
        }

        .alert ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        .alert li {
            margin-bottom: 0.5rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1.5rem;
        }

        .btn {
            padding: 0.875rem 2rem;
            font-weight: 700;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 0.95rem;
        }

        /* Custom Select Wrapper */
        .custom-select-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .custom-select {
            appearance: none;
            padding-right: 2.5rem !important;
        }

        .select-arrow {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: var(--pt-muted);
            pointer-events: none;
            flex-shrink: 0;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
            background-color: color-mix(in srgb, #dc3545 5%, var(--pt-surface-soft));
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--pt-text);
            margin-bottom: 1.5rem;
        }

        .image-input {
            display: none;
        }

        .image-upload-wrapper {
            position: relative;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .card-body {
                padding: 1.5rem !important;
            }
        }

        @media (max-width: 480px) {
            .card-body {
                padding: 1rem !important;
            }

            .page-title {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .form-label-main {
                font-size: 0.8rem;
                margin-bottom: 0.75rem;
            }

            .form-label {
                font-size: 0.8rem;
                margin-bottom: 0.4rem;
            }

            .form-control,
            .form-select {
                padding: 0.6rem 0.75rem;
                font-size: 0.85rem;
                border-radius: 6px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
                margin-bottom: 1rem;
            }

            .form-actions {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn {
                width: 100%;
                padding: 0.65rem 1rem;
                font-size: 0.85rem;
                justify-content: center;
            }

            .image-upload-area {
                padding: 1.25rem;
                border-radius: 8px;
            }

            .upload-icon {
                font-size: 2rem;
                margin-bottom: 0.75rem;
            }

            .upload-main-text {
                font-size: 0.85rem;
            }

            .upload-sub-text {
                font-size: 0.75rem;
            }

            .image-preview-wrapper {
                max-width: 100%;
            }

            .form-textarea {
                min-height: 100px;
                font-size: 0.85rem;
                padding: 0.6rem 0.75rem;
            }

            .character-count {
                font-size: 0.75rem;
            }

            .alert {
                padding: 0.75rem;
                font-size: 0.85rem;
            }

            .alert ul {
                margin: 0;
                padding-left: 1.25rem;
            }

            .alert li {
                font-size: 0.8rem;
                margin-bottom: 0.25rem;
            }
        }
    </style>

    <script>
        const imageInput = document.getElementById('imageInput');
        const imageUploadArea = document.querySelector('.image-upload-area');
        const imagePreviewWrapper = document.getElementById('imagePreviewWrapper');
        const imagePreview = document.getElementById('imagePreview');
        const charCountSpan = document.getElementById('charCount');
        const descriptionField = document.querySelector('textarea[name="description"]');

        // Handle file selection
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                    imagePreviewWrapper.style.display = 'block';
                    imageUploadArea.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle drag and drop
        imageUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            imageUploadArea.style.borderColor = 'var(--pt-primary)';
        });

        imageUploadArea.addEventListener('dragleave', () => {
            imageUploadArea.style.borderColor = 'var(--pt-border)';
        });

        imageUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            imageUploadArea.style.borderColor = 'var(--pt-border)';
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                imageInput.files = e.dataTransfer.files;
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                    imagePreviewWrapper.style.display = 'block';
                    imageUploadArea.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Character count for description
        descriptionField.addEventListener('input', function() {
            charCountSpan.textContent = this.value.length;
        });

        function removeImage(e) {
            e.preventDefault();
            imageInput.value = '';
            imagePreviewWrapper.style.display = 'none';
            imageUploadArea.style.display = 'block';
        }

        // Handle category-specific pricing fields
        const categorySelect = document.querySelector('select[name="category"]');
        const pricingSections = {
            'Feeds': 'feeds-pricing',
            'Vitamins': 'vitamins-pricing',
            'Medicines': 'medicines-pricing',
            'Growth Additives': 'additives-pricing'
        };

        function updatePricingFields() {
            const selectedCategory = categorySelect.value;
            
            // Hide all pricing sections
            document.querySelectorAll('.pricing-section').forEach(section => {
                section.style.display = 'none';
            });

            // Remove required attribute from all pricing inputs
            document.querySelectorAll('.feeds-required, .vitamins-required, .medicines-required, .additives-required').forEach(input => {
                input.removeAttribute('required');
            });

            // Show selected category pricing and set required
            if (selectedCategory && pricingSections[selectedCategory]) {
                const sectionId = pricingSections[selectedCategory];
                document.getElementById(sectionId).style.display = 'block';
                
                // Add required to main price field for this category
                const requiredInputs = document.querySelectorAll(`#${sectionId} .${selectedCategory.toLowerCase().replace(/\s+/g, '-')}-required`);
                requiredInputs.forEach(input => {
                    input.setAttribute('required', 'required');
                });
            }
        }

        // Listen for category changes
        categorySelect.addEventListener('change', updatePricingFields);

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updatePricingFields();
        });
    </script>
@endsection

