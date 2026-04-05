@extends('layouts.admin')

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
        }

        :root[data-theme="dark"] .inventory-search-btn {
            background-color: #ffffff;
            color: #000000;
        }

        :root[data-theme="dark"] .inventory-search-btn:hover {
            background-color: #e5e7eb;
        }

        /* Elevated Card Container */
        .inventory-card {
            background: transparent;
            border: 1px solid;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .inventory-card {
            border-color: #e5e7eb;
            background-color: #ffffff;
        }

        :root[data-theme="dark"] .inventory-card {
            border-color: #2d3748;
            background-color: #111827;
        }

        .inventory-card-body {
            padding: 0;
        }
        }

        .inventory-search-btn:hover {
            background-color: #1f2937;
        }

        :root[data-theme="dark"] .inventory-search-btn {
            background-color: #ffffff;
            color: #000000;
        }

        :root[data-theme="dark"] .inventory-search-btn:hover {
            background-color: #e5e7eb;
        }

        .add-item-btn {
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: #ffffff;
            color: #0f172a;
            text-decoration: none;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }

        .add-item-btn:hover {
            background-color: #f8fafc;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        :root[data-theme="dark"] .add-item-btn {
            background-color: #1e293b;
            color: #f1f5f9;
            border-color: #334155;
        }

        :root[data-theme="dark"] .add-item-btn:hover {
            background-color: #334155;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
        }

        .inventory-table-container {
            border-radius: 0.5rem;
            overflow: hidden;
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

        .stock-entry-btn {
            padding: 0.45rem 1.1rem;
            border: none;
            border-radius: 0.35rem;
            background-color: #10b981;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: capitalize;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .stock-entry-btn:hover {
            background-color: #059669;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
        }

        :root[data-theme="dark"] .stock-entry-btn {
            background-color: #10b981;
        }

        :root[data-theme="dark"] .stock-entry-btn:hover {
            background-color: #059669;
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
                <div class="inventory-search-box">                    <svg class="inventory-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>                    <input type="text" id="inventorySearch" placeholder="Search" class="form-control">
                    <button type="button" class="inventory-search-btn">Search</button>
                </div>
                <a href="#" class="add-item-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M5 12h14"></path>
                    </svg>
                    <span>Add New Item</span>
                </a>
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
                                <th style="width: 20%;">ITEM NAME</th>
                                <th style="width: 15%;">CATEGORY</th>
                                <th style="width: 12%;">PRICE</th>
                                <th style="width: 18%;">SUPPLIER</th>
                                <th style="width: 15%;">CURRENT STOCK</th>
                                <th style="width: 15%;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items ?? [] as $index => $item)
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
                                        <strong>{{ $item['price'] ?? '₱0.00' }}</strong>
                                    </td>
                                    <td>
                                        {{ $item['raiser'] ?? 'Unknown' }}
                                    </td>
                                    <td>
                                        @php
                                            $stock = $item['stock'] ?? 0;
                                            $unit = $item['unit'] ?? 'units';
                                            $stockClass = $stock > 100 ? 'in-stock' : ($stock > 50 ? 'low-stock' : 'restock');
                                        @endphp
                                        <span class="stock-badge {{ $stockClass }}">
                                            {{ $stock }}{{ strtoupper(substr($unit, 0, 1)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="stock-entry-btn" data-product-id="{{ $item['id'] ?? $index }}" data-product-name="{{ $item['name'] ?? 'Unknown' }}" onclick="openStockModal(event)">Stock Entry</a>
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

    <!-- Stock Entry Modal -->
    <div id="stockEntryModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 1050; align-items: center; justify-content: center;">
        <style>
            #stockEntryModal {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .stock-modal-content {
                background: white;
                border-radius: 0.5rem;
                max-width: 500px;
                width: 90%;
                padding: 2rem;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            }

            :root[data-theme="dark"] .stock-modal-content {
                background: #111827;
                color: #f3f4f6;
            }

            .stock-modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .stock-modal-title {
                font-size: 1.5rem;
                font-weight: 700;
                margin: 0;
                color: #0f172a;
            }

            :root[data-theme="dark"] .stock-modal-title {
                color: #f1f5f9;
            }

            .stock-modal-close {
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
                color: #6b7280;
                transition: color 0.2s;
            }

            .stock-modal-close:hover {
                color: #1f2937;
            }

            :root[data-theme="dark"] .stock-modal-close:hover {
                color: #e5e7eb;
            }

            .stock-modal-product-info {
                background-color: #f3f4f6;
                padding: 1rem;
                border-radius: 0.375rem;
                margin-bottom: 1.5rem;
            }

            :root[data-theme="dark"] .stock-modal-product-info {
                background-color: #1f2937;
            }

            .stock-modal-label {
                font-size: 0.85rem;
                color: #6b7280;
                margin: 0 0 0.25rem 0;
            }

            :root[data-theme="dark"] .stock-modal-label {
                color: #9ca3af;
            }

            .stock-modal-product-name {
                font-weight: 700;
                font-size: 1.1rem;
                margin: 0;
                color: #0f172a;
            }

            :root[data-theme="dark"] .stock-modal-product-name {
                color: #f1f5f9;
            }

            .stock-form {
                display: flex;
                flex-direction: column;
                gap: 1.25rem;
            }

            .stock-form-group {
                display: flex;
                flex-direction: column;
            }

            .stock-form-label {
                display: block;
                font-weight: 600;
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
                color: #1f2937;
            }

            :root[data-theme="dark"] .stock-form-label {
                color: #e5e7eb;
            }

            .stock-form-input {
                padding: 0.6rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.375rem;
                font-size: 0.9rem;
                transition: all 0.3s ease;
                background-color: #ffffff;
                color: #1f2937;
            }

            :root[data-theme="dark"] .stock-form-input {
                background-color: #1f2937;
                border-color: #374151;
                color: #f3f4f6;
            }

            .stock-form-input::placeholder {
                color: #9ca3af;
            }

            :root[data-theme="dark"] .stock-form-input::placeholder {
                color: #6b7280;
            }

            .stock-form-input:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            :root[data-theme="dark"] .stock-form-input:focus {
                border-color: #60a5fa;
                box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
            }

            .stock-form-textarea {
                padding: 0.6rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.375rem;
                font-size: 0.9rem;
                resize: vertical;
                min-height: 80px;
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: #ffffff;
                color: #1f2937;
                transition: all 0.3s ease;
            }

            :root[data-theme="dark"] .stock-form-textarea {
                background-color: #1f2937;
                border-color: #374151;
                color: #f3f4f6;
            }

            .stock-form-textarea::placeholder {
                color: #9ca3af;
            }

            :root[data-theme="dark"] .stock-form-textarea::placeholder {
                color: #6b7280;
            }

            .stock-form-textarea:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            :root[data-theme="dark"] .stock-form-textarea:focus {
                border-color: #60a5fa;
                box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
            }

            .stock-form-buttons {
                display: flex;
                gap: 1rem;
                margin-top: 1rem;
            }

            .stock-btn {
                flex: 1;
                padding: 0.6rem;
                border: none;
                border-radius: 0.375rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .stock-btn-submit {
                background-color: #10b981;
                color: white;
            }

            .stock-btn-submit:hover {
                background-color: #059669;
                box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
            }

            .stock-btn-cancel {
                background-color: #e5e7eb;
                color: #1f2937;
                border: 1px solid #d1d5db;
            }

            .stock-btn-cancel:hover {
                background-color: #d1d5db;
                border-color: #9ca3af;
            }

            :root[data-theme="dark"] .stock-btn-cancel {
                background-color: #374151;
                color: #e5e7eb;
                border: 1px solid #4b5563;
            }

            :root[data-theme="dark"] .stock-btn-cancel:hover {
                background-color: #4b5563;
                border-color: #6b7280;
            }

            .stock-success-message {
                display: none;
                margin-top: 1rem;
                padding: 1rem;
                background-color: #d1fae5;
                border: 1px solid #6ee7b7;
                border-radius: 0.375rem;
                color: #065f46;
            }

            :root[data-theme="dark"] .stock-success-message {
                background-color: #064e3b;
                border-color: #10b981;
                color: #d1fae5;
            }

            .page-title {
                font-size: 2rem;
                font-weight: 700;
                color: var(--pt-text);
                margin-bottom: 1.5rem;
            }
        </style>

        <div class="stock-modal-content">
            <div class="stock-modal-header">
                <h2 class="stock-modal-title">Stock Entry</h2>
                <button class="stock-modal-close" onclick="closeStockModal()">×</button>
            </div>

            <div class="stock-modal-product-info">
                <p class="stock-modal-label">Product</p>
                <p class="stock-modal-product-name" id="modalProductName">-</p>
            </div>

            <form id="stockEntryForm" class="stock-form">
                <div class="stock-form-group">
                    <label class="stock-form-label">Movement Type</label>
                    <select id="movementType" name="movement_type" class="stock-form-input" onchange="toggleRaiserField()">
                        <option value="">-- Select Type --</option>
                        <option value="add">Stock In</option>
                        <option value="deduct">Sale</option>
                        <option value="distribute">Distribute to Raiser</option>
                    </select>
                </div>

                <div id="raiserField" style="display: none;" class="stock-form-group">
                    <label class="stock-form-label">Select Raiser</label>
                    <select id="raiserId" name="raiser_id" class="stock-form-input">
                        <option value="">-- Select Raiser --</option>
                        @foreach ($raisers ?? [] as $raiser)
                            <option value="{{ $raiser['id'] ?? $loop->index }}">{{ $raiser['name'] ?? 'Unknown' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="stock-form-group">
                    <label class="stock-form-label">Quantity</label>
                    <input type="number" id="quantity" name="quantity" min="1" class="stock-form-input" required>
                </div>

                <div class="stock-form-group">
                    <label class="stock-form-label">Notes (Optional)</label>
                    <textarea id="notes" name="notes" class="stock-form-textarea"></textarea>
                </div>

                <div class="stock-form-buttons">
                    <button type="submit" class="stock-btn stock-btn-submit">Submit</button>
                    <button type="button" class="stock-btn stock-btn-cancel" onclick="closeStockModal()">Cancel</button>
                </div>
            </form>

            <div id="successMessage" class="stock-success-message">
                Stock entry recorded successfully!
            </div>
        </div>
    </div>

    <script>
        let selectedProductId = null;
        let selectedProductName = null;

        function openStockModal(event) {
            event.preventDefault();
            const btn = event.target;
            selectedProductId = btn.getAttribute('data-product-id');
            selectedProductName = btn.getAttribute('data-product-name');
            
            document.getElementById('modalProductName').textContent = selectedProductName;
            document.getElementById('stockEntryForm').reset();
            document.getElementById('successMessage').style.display = 'none';
            document.getElementById('stockEntryModal').style.display = 'flex';
        }

        function closeStockModal() {
            document.getElementById('stockEntryModal').style.display = 'none';
        }

        function toggleRaiserField() {
            const movementType = document.getElementById('movementType').value;
            const raiserField = document.getElementById('raiserField');
            raiserField.style.display = movementType === 'distribute' ? 'block' : 'none';
            
            if (movementType === 'distribute') {
                document.getElementById('raiserId').required = true;
            } else {
                document.getElementById('raiserId').required = false;
            }
        }

        document.getElementById('stockEntryForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const movementType = document.getElementById('movementType').value;
            const quantity = document.getElementById('quantity').value;
            const raiserId = document.getElementById('raiserId').value;
            const notes = document.getElementById('notes').value;

            if (!movementType || !quantity) {
                alert('Please fill in all required fields');
                return;
            }

            try {
                const response = await fetch('/api/stock-entry', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        product_id: selectedProductId,
                        movement_type: movementType,
                        quantity: parseInt(quantity),
                        raiser_id: raiserId || null,
                        notes: notes || null,
                    }),
                });

                if (response.ok) {
                    document.getElementById('successMessage').style.display = 'block';
                    setTimeout(() => {
                        closeStockModal();
                        window.location.reload();
                    }, 1500);
                } else {
                    alert('Error recording stock entry');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error recording stock entry');
            }
        });

        // Close modal when clicking outside
        document.getElementById('stockEntryModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeStockModal();
            }
        });
    </script>

    <script>
        // Search functionality
        function performSearch() {
            const searchTerm = document.getElementById('inventorySearch').value.toLowerCase();
            const tableRows = document.querySelectorAll('.inventory-table tbody tr');
            let visibleCount = 0;

            tableRows.forEach(row => {
                // Skip the empty state row
                if (row.querySelector('.empty-state')) {
                    return;
                }
                
                const text = row.textContent.toLowerCase();
                const isVisible = text.includes(searchTerm);
                row.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });

            // Show/hide empty state message
            const emptyStateRow = document.querySelector('.inventory-table tbody tr .empty-state')?.closest('tr');
            if (emptyStateRow) {
                emptyStateRow.style.display = visibleCount === 0 ? '' : 'none';
            } else if (visibleCount === 0) {
                // Create empty state if it doesn't exist
                const tbody = document.querySelector('.inventory-table tbody');
                const existingEmpty = tbody.querySelector('.inventory-table tbody tr:last-child');
                
                if (!existingEmpty || !existingEmpty.querySelector('.empty-state')) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.innerHTML = '<td colspan="7" class="empty-state"><p>No items found</p></td>';
                    tbody.appendChild(emptyRow);
                }
            }
        }

        document.querySelector('.inventory-search-btn').addEventListener('click', performSearch);

        // Reset to main content when search input is cleared
        document.getElementById('inventorySearch').addEventListener('input', function(e) {
            if (e.target.value === '') {
                const tableRows = document.querySelectorAll('.inventory-table tbody tr');
                tableRows.forEach(row => {
                    row.style.display = '';
                });
            }
        });

        // Dark mode support
        function initDarkMode() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        }

        initDarkMode();
    </script>
@endsection
