@extends('layouts.cashier')

@php
    $hideTopbarTitle = true;
@endphp

@section('content')
    @php
        $categoriesMap = [
            'Feeds' => 'Available feeds',
            'Vitamins' => 'Available vitamins',
            'Medicines' => 'Available medicines',
            'Others' => 'Available others',
        ];
    @endphp

    <section class="pos-layout">
        <div class="pos-products-area">
            <h1 class="page-title mb-3">POS</h1>
            @foreach ($categoriesMap as $categoryName => $categoryLabel)
                @php
                    $products = $productsByCategory[$categoryName] ?? collect();
                @endphp
                @if (count($products) > 0)
                    <section class="pos-category-card">
                        <h2 class="pos-category-title">{{ $categoryLabel }}</h2>

                        <div class="pos-product-grid">
                            @foreach ($products as $product)
                                <article class="product-card" data-product-id="{{ $product['id'] }}" onclick="stackProduct(event, this)">
                                    <div class="product-image-wrap">
                                        @if ($product['image'])
                                            <img src="{{ Storage::url($product['image']) }}" alt="{{ $product['name'] }}" class="product-image">
                                        @else
                                            <i class="bi bi-shield"></i>
                                        @endif
                                    </div>

                                    <div class="product-meta">
                                        <div class="product-inline">
                                            <span class="product-label">Product Name:</span>
                                            <span class="product-name">{{ $product['name'] }}</span>
                                        </div>
                                        <div class="product-inline">
                                            <span class="product-label">Description:</span>
                                            <span class="product-desc">{{ $product['description'] ?: 'No description available.' }}</span>
                                        </div>
                                    </div>

                                    <div class="price-box">
                                        <span>Price</span>
                                        <strong id="price_{{ $product['id'] }}">P{{ number_format($product['rawPrice'], 2) }}</strong>
                                    </div>

                                    <div class="sold-row">Sold: <span>{{ $product['sales'] }} units</span></div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach
        </div>

        <aside class="order-panel">
            <div class="order-panel-inner">
                <div class="order-head">
                    <h3>Current order</h3>
                    <span class="items-badge" id="orderItemCount">0 items</span>
                </div>

                <div id="orderItems" class="order-items">
                    <p class="order-empty">No products added yet.</p>
                </div>

                <div class="totals">
                    <div><span>Subtotal</span><strong id="subtotal">P0.00</strong></div>
                    <div class="total-row"><span>Total</span><strong id="totalAmount">P0.00</strong></div>
                </div>

                <button type="button" id="completeBtn" class="complete-btn" onclick="completeTransaction()" disabled>Complete Transaction</button>
                <button type="button" id="clearOrderBtn" class="clear-btn" onclick="clearOrder()" disabled>Clear Order</button>
            </div>
        </aside>
    </section>

    <style>
        .pos-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(290px, 360px);
            gap: 1.25rem;
            align-items: start;
            padding: 0.25rem 0 1.25rem;
        }

        .pos-products-area {
            display: grid;
            gap: 1rem;
        }

        .pos-category-card,
        .order-panel-inner {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 8px 20px rgba(19, 34, 59, 0.06);
        }

        .pos-category-title {
            margin: 0 0 0.9rem;
            font-size: 0.92rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--pt-muted);
        }

        .pos-product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 0.85rem;
        }

        .product-card {
            border: 1px solid var(--pt-border);
            border-radius: 0.85rem;
            padding: 0.8rem;
            background: var(--pt-surface-soft);
            display: grid;
            gap: 0.6rem;
            cursor: pointer;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .product-card:hover {
            transform: translateY(-1px);
            border-color: #c8d5e8;
        }

        .product-card.is-stacked {
            border-color: var(--pt-success);
            box-shadow: 0 0 0 3px rgba(67, 203, 137, 0.16);
        }

        .product-image-wrap {
            height: 90px;
            border-radius: 0.7rem;
            background: linear-gradient(180deg, rgba(239, 91, 108, 0.16), rgba(36, 59, 83, 0.12));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--pt-accent);
            font-size: 1.1rem;
        }

        .product-image {
            width: 100%;
            height: 90px;
            object-fit: cover;
            border-radius: 0.7rem;
        }

        .product-name {
            font-size: 0.92rem;
            font-weight: 800;
            color: var(--pt-text);
            line-height: 1.3;
        }

        .product-label {
            font-size: 0.68rem;
            text-transform: uppercase;
            color: var(--pt-muted);
            font-weight: 800;
            letter-spacing: 0.06em;
            margin-bottom: 0;
        }

        .product-inline {
            display: flex;
            align-items: baseline;
            gap: 0.25rem;
            margin-bottom: 0.2rem;
        }

        .product-desc {
            font-size: 0.75rem;
            color: var(--pt-muted);
            line-height: 1.35;
        }

        .price-box {
            border: none;
            border-radius: 0.65rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.55rem 0.65rem;
            background: var(--pt-surface);
        }

        .price-box span {
            font-size: 0.68rem;
            text-transform: uppercase;
            color: var(--pt-muted);
            font-weight: 800;
            letter-spacing: 0.06em;
        }

        .price-box strong {
            color: var(--pt-text);
            font-size: 1.55rem;
            line-height: 1;
            font-weight: 800;
        }

        .sold-row {
            color: var(--pt-muted);
            font-size: 0.75rem;
            display: flex;
            justify-content: space-between;
            gap: 0.5rem;
            margin-top: -0.1rem;
        }

        .sold-row span {
            color: var(--pt-text);
            font-weight: 700;
        }

        .order-panel {
            position: sticky;
            top: 108px;
        }

        .order-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            gap: 0.75rem;
        }

        .order-panel h3 {
            margin: 0;
            font-size: 1.02rem;
            color: var(--pt-text);
            font-weight: 800;
        }

        .items-badge {
            font-size: 0.72rem;
            color: var(--pt-text);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .order-items {
            max-height: 310px;
            overflow-y: auto;
            margin-bottom: 0.85rem;
            padding-right: 0.2rem;
        }

        .order-empty {
            font-size: 0.85rem;
            color: var(--pt-muted);
            margin: 0.4rem 0 0.2rem;
        }

        .order-item {
            border-bottom: 1px solid var(--pt-border);
            padding: 0.65rem 0;
        }

        .order-item:last-child {
            border-bottom: 0;
            padding-bottom: 0.15rem;
        }

        .order-line-1 {
            display: flex;
            justify-content: space-between;
            gap: 0.6rem;
            font-size: 0.86rem;
            color: var(--pt-text);
        }

        .order-line-2 {
            display: flex;
            justify-content: space-between;
            gap: 0.6rem;
            font-size: 0.76rem;
            color: var(--pt-muted);
            margin-top: 0.2rem;
        }

        .qty-ctrl {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .qty-ctrl button {
            width: 20px;
            height: 20px;
            border: 1px solid var(--pt-border);
            border-radius: 0.35rem;
            background: var(--pt-surface-soft);
            color: var(--pt-text);
            line-height: 1;
        }

        .totals {
            border-top: 1px solid var(--pt-border);
            margin-top: 0.65rem;
            padding-top: 0.75rem;
        }

        .totals > div {
            display: flex;
            justify-content: space-between;
            font-size: 0.84rem;
            color: var(--pt-text);
            margin-bottom: 0.4rem;
        }

        .total-row span,
        .total-row strong {
            color: var(--pt-text);
            font-size: 0.98rem;
            font-weight: 800;
        }

        .complete-btn {
            margin-top: 0.7rem;
            width: 100%;
            min-height: 42px;
            border: 1px solid var(--pt-primary);
            border-radius: 0.7rem;
            background: var(--pt-primary);
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        :root[data-theme="dark"] .complete-btn:not(:disabled) {
            background: #ffffff;
            border-color: #ffffff;
            color: #0f1f33;
            -webkit-text-fill-color: #0f1f33;
        }

        .complete-btn:hover {
            opacity: 0.92;
        }

        .complete-btn:disabled,
        .clear-btn:disabled {
            opacity: 1;
            cursor: not-allowed;
        }

        .complete-btn:disabled {
            background: #7f8896 !important;
            border-color: #7f8896 !important;
            color: #e8edf5 !important;
            -webkit-text-fill-color: #e8edf5;
        }

        :root[data-theme="dark"] .complete-btn:disabled {
            background: #ffffff !important;
            border-color: #ffffff !important;
            color: #6f7d92 !important;
            -webkit-text-fill-color: #6f7d92;
        }

        .clear-btn {
            margin-top: 0.55rem;
            width: 100%;
            min-height: 38px;
            border: 1px solid var(--pt-border);
            border-radius: 0.7rem;
            background: var(--pt-surface-soft);
            color: var(--pt-text);
            font-size: 0.82rem;
            font-weight: 700;
        }

        :root[data-theme="dark"] .clear-btn:disabled {
            background: #1f2a3d;
            border-color: #2f3c52;
            color: #a9b5c9;
        }

        @media (max-width: 1199.98px) {
            .pos-layout {
                grid-template-columns: 1fr;
            }

            .order-panel {
                position: static;
            }
        }

        @media (max-width: 575.98px) {
            .pos-product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        const API_BASE = '/api/cashier/quick-sale';
        let quickSaleItems = [];

        function formatMoney(value) {
            return `P${Number(value || 0).toFixed(2)}`;
        }

        async function parseJsonSafe(response) {
            const text = await response.text();
            try {
                return JSON.parse(text);
            } catch (error) {
                throw new Error(`Server returned an invalid response (HTTP ${response.status}).`);
            }
        }

        async function loadSession() {
            try {
                const response = await fetch(`${API_BASE}/session`);
                const data = await parseJsonSafe(response);
                quickSaleItems = data.items || [];
                renderOrderSummary();
            } catch (error) {
                console.error('Load session error:', error);
            }
        }

        async function addToQuickSale(productId, quantity) {
            const qty = parseInt(quantity || '0', 10);
            if (qty < 1) return;

            const response = await fetch(`${API_BASE}/add-item`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ product_id: productId, quantity: qty }),
            });

            const data = await parseJsonSafe(response);
            quickSaleItems = data.items || [];
            renderOrderSummary();
        }

        async function changeQty(itemId, qty) {
            if (qty < 1) {
                await removeItem(itemId);
                return;
            }

            const response = await fetch(`${API_BASE}/item/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ quantity: qty }),
            });

            const data = await parseJsonSafe(response);
            quickSaleItems = data.items || [];
            renderOrderSummary();
        }

        async function removeItem(itemId) {
            const response = await fetch(`${API_BASE}/item/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
            });

            const data = await parseJsonSafe(response);
            quickSaleItems = data.items || [];
            renderOrderSummary();
        }

        async function stackProduct(event, card) {
            if (event.target.closest('button, input, select, option')) {
                return;
            }
            const productId = card?.dataset?.productId;
            if (!productId) return;

            await addToQuickSale(productId, 1);
            card.classList.add('is-stacked');
            window.setTimeout(() => card.classList.remove('is-stacked'), 220);
        }

        function renderOrderSummary() {
            const holder = document.getElementById('orderItems');
            const countNode = document.getElementById('orderItemCount');
            const completeBtn = document.getElementById('completeBtn');
            const clearOrderBtn = document.getElementById('clearOrderBtn');

            if (!quickSaleItems || quickSaleItems.length === 0) {
                holder.innerHTML = '<p class="order-empty">No products added yet.</p>';
                countNode.textContent = '0 items';
                document.getElementById('subtotal').textContent = 'P0.00';
                document.getElementById('totalAmount').textContent = 'P0.00';
                completeBtn.disabled = true;
                clearOrderBtn.disabled = true;
                return;
            }

            countNode.textContent = `${quickSaleItems.length} items`;
            holder.innerHTML = quickSaleItems.map((item) => {
                const price = Number(item.unit_price || 0);
                const lineTotal = Number(item.net_price || 0);
                return `
                    <div class="order-item">
                        <div class="order-line-1">
                            <strong>${item.product?.name || 'Product'}</strong>
                            <strong>${formatMoney(lineTotal)}</strong>
                        </div>
                        <div class="order-line-2">
                            <span>${formatMoney(price)} each</span>
                            <span class="qty-ctrl">
                                <button type="button" onclick="changeQty(${item.id}, ${item.quantity - 1})">-</button>
                                <b>${item.quantity}</b>
                                <button type="button" onclick="changeQty(${item.id}, ${item.quantity + 1})">+</button>
                            </span>
                        </div>
                    </div>
                `;
            }).join('');

            const subtotal = quickSaleItems.reduce((sum, item) => sum + Number(item.total_price || 0), 0);
            document.getElementById('subtotal').textContent = formatMoney(subtotal);
            document.getElementById('totalAmount').textContent = formatMoney(subtotal);
            completeBtn.disabled = false;
            clearOrderBtn.disabled = false;
        }

        async function completeTransaction() {
            if (!quickSaleItems || quickSaleItems.length === 0) return;

            try {
                await fetch(`${API_BASE}/session`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({ customer_name: 'Walk-in' }),
                });

                await fetch(`${API_BASE}/confirm`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                });

                alert('Transaction completed.');
                await loadSession();
            } catch (error) {
                alert('Transaction failed');
            }
        }

        async function clearOrder() {
            if (!quickSaleItems || quickSaleItems.length === 0) return;
            if (!confirm('Clear current order?')) return;

            await fetch(`${API_BASE}/cancel`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
            });

            quickSaleItems = [];
            renderOrderSummary();
        }

        document.addEventListener('DOMContentLoaded', loadSession);
    </script>
@endsection

