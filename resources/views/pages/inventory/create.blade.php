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
                            <h1 class="page-title mb-4">Add Inventory Item</h1>
                            <form method="POST" action="{{ route('inventory.store') }}" class="inventory-create-form">
                                @csrf

                                <div class="row">
                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="name" class="form-label">Item Name</label>
                                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ex. Booster Feed" required>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="category" class="form-label">Category</label>
                                        <select id="category" name="category" class="form-select @error('category') is-invalid @enderror" required>
                                            <option value="">Select category</option>
                                            <option value="FEEDS" @selected(old('category') === 'FEEDS')>FEEDS</option>
                                            <option value="VITAMINS" @selected(old('category') === 'VITAMINS')>VITAMINS</option>
                                            <option value="MEDICINE" @selected(old('category') === 'MEDICINE')>MEDICINE</option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="cost" class="form-label">Cost</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" id="cost" name="cost" class="form-control no-spinner @error('cost') is-invalid @enderror" value="{{ old('cost') }}" placeholder="0.00" min="0" step="0.01" required>
                                        </div>
                                        @error('cost')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="supplier" class="form-label">Supplier</label>
                                        <input type="text" id="supplier" name="supplier" class="form-control @error('supplier') is-invalid @enderror" value="{{ old('supplier') }}" placeholder="Ex. Agri-Bio Logistics" required>
                                        @error('supplier')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="stock" class="form-label">Current Stock</label>
                                        <input type="number" id="stock" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" placeholder="0" min="0" required>
                                        @error('stock')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="status" class="form-label">Stock Status</label>
                                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="instock" @selected(old('status', 'instock') === 'instock')>In Stock</option>
                                            <option value="low_stock" @selected(old('status') === 'low_stock')>Low Stock</option>
                                            <option value="critical" @selected(old('status') === 'critical')>Critical</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex gap-3 mt-4">
                                    <button type="submit" class="btn btn-dark grow">
                                        <i class="bi bi-plus me-2"></i>Add Item
                                    </button>
                                    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary grow">Cancel</a>
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
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .section-heading {
            color: #ffffff;
        }

        .section-subheading {
            color: var(--pt-muted);
            font-size: 0.95rem;
        }

        .inventory-create-form .form-label {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--pt-muted);
            margin-bottom: 0.65rem;
        }

        :root[data-theme="dark"] .inventory-create-form .form-label {
            color: #cbd5e0;
        }

        .inventory-create-form .form-control,
        .inventory-create-form .form-select {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            color: var(--pt-text);
            border-radius: 8px;
            padding: 0.72rem 0.95rem;
        }

        .inventory-create-form .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%239cb0c9' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.8rem center;
            background-size: 14px 14px;
            padding-right: 2.4rem;
            cursor: pointer;
        }

        .inventory-create-form .form-select option {
            background: var(--pt-surface);
            color: var(--pt-text);
        }

        .inventory-create-form .form-control:focus,
        .inventory-create-form .form-select:focus {
            background: var(--pt-surface);
            border-color: #5b8def;
            color: var(--pt-text);
            box-shadow: 0 0 0 3px rgba(91, 141, 239, 0.14);
        }

        .inventory-create-form .form-control::placeholder {
            color: var(--pt-muted);
        }

        .inventory-create-form .input-group {
            border: 1px solid var(--pt-border);
            border-radius: 0.5rem;
            overflow: hidden;
            background: var(--pt-surface-soft);
        }

        .inventory-create-form .input-group:focus-within {
            border-color: #5b8def;
            box-shadow: 0 0 0 3px rgba(91, 141, 239, 0.14);
        }

        .inventory-create-form .input-group-text {
            border: 0;
            background: transparent;
            color: var(--pt-text);
            font-weight: 700;
            padding-inline: 0.85rem;
        }

        .inventory-create-form .input-group .form-control {
            border: 0;
            background: transparent;
        }

        .inventory-create-form .input-group .form-control:focus {
            box-shadow: none;
            outline: 0;
        }

        .inventory-create-form .no-spinner::-webkit-outer-spin-button,
        .inventory-create-form .no-spinner::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .inventory-create-form .no-spinner[type=number] {
            -moz-appearance: textfield;
        }

        .btn-primary {
            background: #5b8def;
            border: 0;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            padding: 0.72rem 1.2rem;
        }

        .btn-primary:hover {
            background: #4a7fc8;
            color: #fff;
        }

        .btn-secondary {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            border-radius: 8px;
            color: var(--pt-text);
            font-weight: 600;
            padding: 0.72rem 1.2rem;
        }

        .btn-secondary:hover {
            background: var(--pt-surface);
            color: var(--pt-text);
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

        @media (max-width: 768px) {
            .card-body {
                padding: 1.3rem !important;
            }

            .section-heading {
                font-size: 1.35rem;
            }
        }
    </style>
@endsection
