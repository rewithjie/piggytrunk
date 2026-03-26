@extends('layouts.admin')

@section('content')
    <section class="inventory-page">
        <div class="row g-4 mb-4">
            @foreach ($items as $item)
                <div class="col-12 col-md-6 col-xl-3">
                    <article class="card dashboard-bootstrap-card inventory-product-card h-100">
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="inventory-product-image">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <div>
                                    <span class="inventory-item-category">{{ $item['category'] }}</span>
                                    <h3 class="inventory-item-title mb-1">{{ $item['name'] }}</h3>
                                </div>
                                <span class="inventory-stock-pill">{{ $item['stock'] }} {{ $item['unit'] }}</span>
                            </div>
                            <div class="inventory-item-price mb-3">{{ $item['price'] }}</div>
                            <div class="inventory-source">Source: {{ $item['raiser'] }}</div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-xl-7">
                <div class="card dashboard-bootstrap-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="section-label mb-1">Sales</p>
                                <h3 class="chart-title mb-0">Customer Purchases</h3>
                            </div>
                            <span class="chart-badge badge text-bg-light">Latest Sales</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle dashboard-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Item Purchased</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>From Stock</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $purchase)
                                        <tr>
                                            <td class="fw-semibold">{{ $purchase['customer'] }}</td>
                                            <td>{{ $purchase['item'] }}</td>
                                            <td>{{ $purchase['quantity'] }}</td>
                                            <td class="fw-semibold">{{ $purchase['price'] }}</td>
                                            <td>{{ $purchase['source'] }}</td>
                                            <td>{{ $purchase['date'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="card dashboard-bootstrap-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="section-label mb-1">Raiser Stock</p>
                                <h3 class="chart-title mb-0">Inventory Tracking</h3>
                            </div>
                        </div>

                        <div class="inventory-tracker-list">
                            @foreach ($raiserInventory as $record)
                                <div class="inventory-tracker-row">
                                    <div class="d-flex justify-content-between gap-3 align-items-start mb-2">
                                        <div>
                                            <div class="inventory-item-title inventory-item-title-sm">{{ $record['item'] }}</div>
                                            <div class="table-meta">{{ $record['raiser'] }} • {{ $record['category'] }}</div>
                                        </div>
                                        <span class="inventory-stock-pill">{{ $record['remaining'] }} left</span>
                                    </div>
                                    <div class="row g-2 inventory-mini-stats">
                                        <div class="col-4">
                                            <div class="inventory-mini-label">Opening</div>
                                            <div class="inventory-mini-value">{{ $record['opening'] }}</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="inventory-mini-label">Sold</div>
                                            <div class="inventory-mini-value">{{ $record['sold'] }}</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="inventory-mini-label">Remaining</div>
                                            <div class="inventory-mini-value">{{ $record['remaining'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card dashboard-bootstrap-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="section-label mb-1">Stock Movement</p>
                        <h3 class="chart-title mb-0">Items Sold From Hog Raiser Stock</h3>
                    </div>
                    <span class="chart-badge badge text-bg-light">Feeds • Vitamins • Medicines</span>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle dashboard-table mb-0">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Hog Raiser</th>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Quantity Sold</th>
                                <th>Date Sold</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($soldFromStock as $sale)
                                <tr>
                                    <td class="fw-semibold">{{ $sale['customer'] }}</td>
                                    <td>{{ $sale['raiser'] }}</td>
                                    <td>{{ $sale['item'] }}</td>
                                    <td>{{ $sale['category'] }}</td>
                                    <td>{{ $sale['quantity'] }}</td>
                                    <td>{{ $sale['sold_at'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
