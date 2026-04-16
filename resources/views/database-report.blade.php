<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PiggyTrunk Database Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .content {
            padding: 40px 30px;
        }

        .section {
            margin-bottom: 40px;
        }

        .section-title {
            display: flex;
            align-items: center;
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
        }

        .section-title span {
            font-size: 1.3em;
            margin-right: 12px;
        }

        .section-count {
            margin-left: auto;
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
        }

        .records-grid {
            display: grid;
            gap: 15px;
        }

        .record {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .record:hover {
            background: #f0f1ff;
            transform: translateX(5px);
        }

        .record-item {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 10px;
        }

        .record-field {
            font-size: 0.95em;
        }

        .record-field-label {
            color: #667eea;
            font-weight: 600;
            margin-right: 8px;
        }

        .record-field-value {
            color: #333;
        }

        .empty {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
            color: #856404;
            font-size: 1.1em;
        }

        .empty.cleaned {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            margin-top: 40px;
        }

        .summary-title {
            font-size: 1.5em;
            margin-bottom: 20px;
            text-align: center;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .summary-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid rgba(255, 255, 255, 0.5);
        }

        .summary-label {
            font-size: 0.9em;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 1.8em;
            font-weight: bold;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #666;
            border-top: 1px solid #ddd;
        }

        .badge-cleaned {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 0.85em;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🐷 PiggyTrunk Database Report</h1>
            <p>✅ Cleaned Database - All Dump Data Removed</p>
        </div>

        <div class="content">
            <!-- Users Section -->
            <div class="section">
                <div class="section-title">
                    <span>👤</span> USERS
                    <span class="section-count">{{ $users->count() }} record{{ $users->count() != 1 ? 's' : '' }}</span>
                </div>
                <div class="records-grid">
                    @forelse($users as $user)
                        <div class="record">
                            <div class="record-item">
                                <div class="record-field">
                                    <span class="record-field-label">ID:</span>
                                    <span class="record-field-value">{{ $user->id }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Name:</span>
                                    <span class="record-field-value">{{ $user->name }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Email:</span>
                                    <span class="record-field-value">{{ $user->email }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty">No users found</div>
                    @endforelse
                </div>
            </div>

            <!-- Raisers Section -->
            <div class="section">
                <div class="section-title">
                    <span>🐷</span> RAISERS
                    <span class="section-count">{{ $raisers->count() }} record{{ $raisers->count() != 1 ? 's' : '' }}</span>
                </div>
                <div class="records-grid">
                    @forelse($raisers as $raiser)
                        <div class="record">
                            <div class="record-item">
                                <div class="record-field">
                                    <span class="record-field-label">ID:</span>
                                    <span class="record-field-value">{{ $raiser->id }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Name:</span>
                                    <span class="record-field-value">{{ $raiser->name }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Status:</span>
                                    <span class="record-field-value">{{ $raiser->status ?? 'Active' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty cleaned">✅ All dump raisers removed - Ready for new data</div>
                    @endforelse
                </div>
            </div>

            <!-- Pig Types Section -->
            <div class="section">
                <div class="section-title">
                    <span>🐽</span> PIG TYPES
                    <span class="section-count">{{ $pigTypes->count() }} record{{ $pigTypes->count() != 1 ? 's' : '' }}</span>
                </div>
                <div class="records-grid">
                    @forelse($pigTypes as $type)
                        <div class="record">
                            <div class="record-item">
                                <div class="record-field">
                                    <span class="record-field-label">ID:</span>
                                    <span class="record-field-value">{{ $type->id }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Type:</span>
                                    <span class="record-field-value">{{ $type->type_name }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Code:</span>
                                    <span class="record-field-value">{{ $type->code }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty">No pig types found</div>
                    @endforelse
                </div>
            </div>

            <!-- Batches Section -->
            <div class="section">
                <div class="section-title">
                    <span>📦</span> BATCHES
                    <span class="section-count">{{ $batches->count() }} record{{ $batches->count() != 1 ? 's' : '' }}</span>
                </div>
                <div class="records-grid">
                    @forelse($batches as $batch)
                        <div class="record">
                            <div class="record-item">
                                <div class="record-field">
                                    <span class="record-field-label">ID:</span>
                                    <span class="record-field-value">{{ $batch->id }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Code:</span>
                                    <span class="record-field-value">{{ $batch->batch_code }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Raiser:</span>
                                    <span class="record-field-value">{{ $batch->raiser?->name ?? 'N/A' }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Type:</span>
                                    <span class="record-field-value">{{ $batch->pigType?->type_name ?? 'N/A' }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Quantity:</span>
                                    <span class="record-field-value">{{ $batch->quantity }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Status:</span>
                                    <span class="record-field-value">{{ $batch->status ?? 'Active' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty cleaned">✅ All dump batches removed - Ready for new data</div>
                    @endforelse
                </div>
            </div>

            <!-- Investments Section -->
            <div class="section">
                <div class="section-title">
                    <span>💰</span> INVESTMENTS
                    <span class="section-count">{{ $investments->count() }} record{{ $investments->count() != 1 ? 's' : '' }}</span>
                </div>
                <div class="records-grid">
                    @forelse($investments as $investment)
                        <div class="record">
                            <div class="record-item">
                                <div class="record-field">
                                    <span class="record-field-label">ID:</span>
                                    <span class="record-field-value">{{ $investment->id }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Code:</span>
                                    <span class="record-field-value">{{ $investment->investment_code }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Batch:</span>
                                    <span class="record-field-value">{{ $investment->batch?->batch_code ?? 'N/A' }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Amount:</span>
                                    <span class="record-field-value">₱{{ number_format($investment->amount, 2) }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Status:</span>
                                    <span class="record-field-value">{{ $investment->status ?? 'Active' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty cleaned">✅ All dump investments removed - Ready for new data</div>
                    @endforelse
                </div>
            </div>

            <!-- Investors Section -->
            <div class="section">
                <div class="section-title">
                    <span>👥</span> INVESTORS
                    <span class="section-count">{{ $investors->count() }} record{{ $investors->count() != 1 ? 's' : '' }}</span>
                </div>
                <div class="records-grid">
                    @forelse($investors as $investor)
                        <div class="record">
                            <div class="record-item">
                                <div class="record-field">
                                    <span class="record-field-label">ID:</span>
                                    <span class="record-field-value">{{ $investor->id }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Name:</span>
                                    <span class="record-field-value">{{ $investor->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty cleaned">✅ Empty - Ready for new investor data</div>
                    @endforelse
                </div>
            </div>

            <!-- Retail Products Section -->
            <div class="section">
                <div class="section-title">
                    <span>🛒</span> RETAIL PRODUCTS
                    <span class="section-count">{{ $retailProducts->count() }} record{{ $retailProducts->count() != 1 ? 's' : '' }}</span>
                </div>
                <div class="records-grid">
                    @forelse($retailProducts as $product)
                        <div class="record">
                            <div class="record-item">
                                <div class="record-field">
                                    <span class="record-field-label">ID:</span>
                                    <span class="record-field-value">{{ $product->id }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Name:</span>
                                    <span class="record-field-value">{{ $product->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty cleaned">
                            ✅ No retail products (Cleaned successfully) <span class="badge-cleaned">CLEAN</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Lifecycle Stages Section -->
            <div class="section">
                <div class="section-title">
                    <span>📊</span> LIFECYCLE STAGES
                    <span class="section-count">{{ $lifecycleStages->count() }} record{{ $lifecycleStages->count() != 1 ? 's' : '' }}</span>
                </div>
                <div class="records-grid">
                    @forelse($lifecycleStages as $stage)
                        <div class="record">
                            <div class="record-item">
                                <div class="record-field">
                                    <span class="record-field-label">ID:</span>
                                    <span class="record-field-value">{{ $stage->id }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Stage:</span>
                                    <span class="record-field-value">{{ $stage->stage_name ?? 'N/A' }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Duration:</span>
                                    <span class="record-field-value">{{ $stage->duration_days ?? 'N/A' }} days</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty">No lifecycle stages found</div>
                    @endforelse
                </div>
            </div>

            <!-- Inventory Categories Section -->
            <div class="section">
                <div class="section-title">
                    <span>📂</span> INVENTORY CATEGORIES
                    <span class="section-count">{{ $inventoryCategories->count() }} record{{ $inventoryCategories->count() != 1 ? 's' : '' }}</span>
                </div>
                <div class="records-grid">
                    @forelse($inventoryCategories as $category)
                        <div class="record">
                            <div class="record-item">
                                <div class="record-field">
                                    <span class="record-field-label">ID:</span>
                                    <span class="record-field-value">{{ $category->id }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Name:</span>
                                    <span class="record-field-value">{{ $category->name ?? 'N/A' }}</span>
                                </div>
                                <div class="record-field">
                                    <span class="record-field-label">Code:</span>
                                    <span class="record-field-value">{{ $category->code ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty">No inventory categories found</div>
                    @endforelse
                </div>
            </div>

            <!-- Summary Section -->
            <div class="summary">
                <div class="summary-title">📈 Summary Statistics</div>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-label">Total Users</div>
                        <div class="summary-value">{{ $users->count() }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Total Raisers</div>
                        <div class="summary-value">{{ $raisers->count() }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Total Batches</div>
                        <div class="summary-value">{{ $batches->count() }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Total Investments</div>
                        <div class="summary-value">{{ $investments->count() }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Total Investors</div>
                        <div class="summary-value">{{ $investors->count() }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Retail Products</div>
                        <div class="summary-value">{{ $retailProducts->count() }} ✅</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Lifecycle Stages</div>
                        <div class="summary-value">{{ $lifecycleStages->count() }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Inventory Categories</div>
                        <div class="summary-value">{{ $inventoryCategories->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>🐷 PiggyTrunk Database Report | Cleaned & Refreshed on {{ now()->format('F j, Y \a\t g:i A') }}</p>
        </div>
    </div>
</body>
</html>
