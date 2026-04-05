@extends('layouts.admin')

@section('content')
    <div class="card dashboard-bootstrap-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <p class="section-label mb-1">Retail Shop</p>
                    <h2 class="section-heading mb-0">Edit Product</h2>
                </div>
                <a href="{{ route('retail.index') }}" class="btn btn-outline-secondary">Back to Retail</a>
            </div>

            <form method="POST" action="{{ route('retail.products.update', $product->id) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected(old('category', $product->category) === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stock</label>
                    <input type="number" min="0" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Save Changes</button>
                    <a href="{{ route('retail.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px;
            padding-right: 2.5rem;
        }

        :root[data-theme="dark"] .form-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3E%3C/svg%3E");
        }
    </style>
@endsection

