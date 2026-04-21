@extends('layouts.cashier')

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
                            <h1 class="page-title mb-4">Add New Product</h1>

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

                            <form method="POST" action="{{ route('cashier.retail.products.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Product Name *</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-sm-6">
                                        <label class="form-label">Category *</label>
                                        <select name="category" class="form-select" required>
                                            <option value="">Select category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label class="form-label">Stock *</label>
                                        <input type="number" min="0" name="stock" class="form-control" value="{{ old('stock') }}" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Price (PHP) *</label>
                                    <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Product Photo</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Add Product</button>
                                    <a href="{{ route('cashier.inventory') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

