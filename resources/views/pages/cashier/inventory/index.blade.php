@extends('layouts.cashier')

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
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        :root[data-theme="dark"] .inventory-search-btn {
            background-color: #000000;
            color: #ffffff;
        }

        :root[data-theme="dark"] .inventory-search-btn:hover {
            background-color: #1f2937;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px 0 rgba(0, 0, 0, 0.2);
        }

        .inventory-card {
            background: transparent;
        }

        .inventory-card-body {
            background: transparent;
        }

        .inventory-table-container {
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid;
        }

        /* Light mode */
        .inventory-table-container {
            border-color: #e5e7eb;
            background: #ffffff;
        }

        /* Dark mode */
        :root[data-theme="dark"] .inventory-table-container {
            border-color: #1e293b;
            background: var(--pt-surface);
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

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #64748b;
            font-size: 0.95rem;
        }

        :root[data-theme="dark"] .empty-state {
            color: #94a3b8;
        }
    </style>

    <h1 class="page-title mb-5">Inventory</h1>
    <section class="inventory-page">
        <!-- Controls -->
        <div class="inventory-controls">
            <div class="inventory-search-box">
                <svg class="inventory-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <input type="text" id="inventorySearch" placeholder="Search" class="form-control">
                <button type="button" class="inventory-search-btn">Search</button>
            </div>
        </div>

        <!-- Inventory List -->
        <div class="inventory-card">
            <div class="inventory-card-body">
                <div class="inventory-table-container">
                    <table class="inventory-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 25%;">ITEM NAME</th>
                                <th style="width: 20%;">CATEGORY</th>
                                <th style="width: 15%;">QUANTITY</th>
                                <th style="width: 20%;">DESCRIPTION</th>
                                <th style="width: 15%;">STATUS</th>
                                <th style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($itemsByCategory->flatten() as $index => $item)
                                <tr>
                                    <td>
                                        <span class="inventory-row-number">{{ $index + 1 }}</span>
                                    </td>
                                    <td>
                                        <span class="item-name">{{ $item['name'] ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="category-badge">{{ $item['category'] ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $item['quantity'] ?? 0 }} {{ $item['unit'] ?? 'units' }}</strong>
                                    </td>
                                    <td>
                                        {{ Str::limit($item['description'] ?? 'N/A', 40) }}
                                    </td>
                                    <td>
                                        @php
                                            $quantity = $item['quantity'] ?? 0;
                                            $stockClass = $quantity > 50 ? 'in-stock' : ($quantity > 20 ? 'low-stock' : 'restock');
                                        @endphp
                                        <span class="stock-badge {{ $stockClass }}">
                                            {{ $quantity > 50 ? 'In Stock' : ($quantity > 20 ? 'Low Stock' : 'Critical') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <input type="number" class="form-control" style="width: 60px; height: 36px; padding: 0.25rem 0.5rem; font-size: 0.85rem;" data-item-id="{{ $item['id'] ?? $index }}" data-item-name="{{ $item['name'] ?? 'Unknown' }}" placeholder="0" min="1">
                                            <button class="btn btn-sm btn-primary" style="padding: 0.4rem 0.75rem; font-size: 0.85rem;" onclick="addInventoryToQuickSale(this)" title="Add to Quick Sale">
                                                <i class="bi bi-cart-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="empty-state">
                                        <p>No inventory items found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script>
        async function addInventoryToQuickSale(button) {
            const row = button.closest('tr');
            const input = row.querySelector('input[type="number"]');
            const quantity = parseInt(input.value) || 0;
            const itemId = input.getAttribute('data-item-id');
            const itemName = input.getAttribute('data-item-name');

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
    </script>
@endsection
