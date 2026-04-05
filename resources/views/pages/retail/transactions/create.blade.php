@extends('layouts.admin')

@section('content')
    <div class="card dashboard-bootstrap-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <p class="section-label mb-1">Retail Shop</p>
                    <h2 class="section-heading mb-0">Add Transaction</h2>
                </div>
                <a href="{{ route('retail.index') }}" class="btn btn-outline-secondary">Back to Retail</a>
            </div>

            <form method="POST" action="{{ route('retail.transactions.store') }}" class="row g-3">
                @csrf

                <div class="col-md-4">
                    <label class="form-label">Product</label>
                    <select name="retail_product_id" class="form-select" required>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(old('retail_product_id') == $product->id)>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hog Raiser (Optional)</label>
                    <select name="raiser_id" class="form-select">
                        <option value="">None</option>
                        @foreach ($raisers as $raiser)
                            <option value="{{ $raiser->id }}" @selected(old('raiser_id') == $raiser->id)>{{ $raiser->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Customer Name (if no raiser)</label>
                    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity</label>
                    <input type="number" min="1" name="quantity" class="form-control" value="{{ old('quantity', 1) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Channel</label>
                    <select name="channel" class="form-select" required>
                        @foreach ($transactionChannels as $channel)
                            <option value="{{ $channel }}" @selected(old('channel') === $channel)>{{ $channel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach ($transactionStatuses as $status)
                            <option value="{{ $status }}" @selected(old('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date</label>
                    <input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date', now()->toDateString()) }}" required>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Save Transaction</button>
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

