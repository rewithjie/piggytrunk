@extends('layouts.admin')

@section('content')
    <div class="card dashboard-bootstrap-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <p class="section-label mb-1">Retail Shop</p>
                    <h2 class="section-heading mb-0">Edit Transaction</h2>
                </div>
                <a href="{{ route('retail.index') }}" class="btn btn-outline-secondary">Back to Retail</a>
            </div>

            <form method="POST" action="{{ route('retail.transactions.update', $transaction->id) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-4">
                    <label class="form-label">Product</label>
                    <select name="retail_product_id" class="form-select" required>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(old('retail_product_id', $transaction->retail_product_id) == $product->id)>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hog Raiser (Optional)</label>
                    <select name="raiser_id" class="form-select">
                        <option value="">None</option>
                        @foreach ($raisers as $raiser)
                            <option value="{{ $raiser->id }}" @selected(old('raiser_id', $transaction->raiser_id) == $raiser->id)>{{ $raiser->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Customer Name (if no raiser)</label>
                    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $transaction->customer_name) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity</label>
                    <input type="number" min="1" name="quantity" class="form-control" value="{{ old('quantity', $transaction->quantity) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Channel</label>
                    <select name="channel" class="form-select" required>
                        @foreach ($transactionChannels as $channel)
                            <option value="{{ $channel }}" @selected(old('channel', $transaction->channel) === $channel)>{{ $channel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach ($transactionStatuses as $status)
                            <option value="{{ $status }}" @selected(old('status', $transaction->status) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date</label>
                    <input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date', $transaction->transaction_date?->toDateString()) }}" required>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Save Changes</button>
                    <a href="{{ route('retail.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

