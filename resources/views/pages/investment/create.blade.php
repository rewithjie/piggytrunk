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
                            <h1 class="page-title mb-5">Create Investment</h1>
                            <form action="{{ route('investments.store') }}" method="POST" class="investment-form">
                                @csrf

                                <!-- HOG RAISER -->
                                <div class="mb-4">
                                    <label for="raiser_id" class="form-label">
                                        <i class="bi bi-person me-2"></i>HOG RAISER
                                    </label>
                                    <select id="raiser_id" name="raiser_id" class="form-select raiser-select" required>
                                        <option value="">Select an authorized raiser</option>
                                        @foreach ($raisers as $raiser)
                                            <option value="{{ $raiser->id }}" 
                                                    data-pig-type="{{ $raiser->pigType?->name ?? 'Fattening' }}"
                                                    data-initials="{{ strtoupper(implode('', array_map(fn($word) => $word[0], explode(' ', $raiser->name)))) }}">
                                                {{ $raiser->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('raiser_id')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Two Column Layout -->
                                <div class="row">
                                    <!-- INITIAL CAPITAL -->
                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="initial_capital" class="form-label">
                                            <i class="bi bi-cash-coin me-2"></i>INITIAL CAPITAL
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" id="initial_capital" name="initial_capital" class="form-control no-spinner" 
                                                   placeholder="0.00" step="0.01" min="0" required>
                                        </div>
                                        @error('initial_capital')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- HOG TYPE -->
                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="hog_type" class="form-label">
                                            <i class="bi bi-diagram-2 me-2"></i>HOG TYPE
                                        </label>
                                        <input type="text" id="hog_type_display" class="form-control" placeholder="Auto-populated" readonly style="background-color: var(--pt-surface); cursor: not-allowed;">
                                        <input type="hidden" id="hog_type" name="hog_type" value="">
                                        @error('hog_type')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Two Column Layout -->
                                <div class="row">
                                    <!-- TOTAL HOG -->
                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="total_hog" class="form-label">
                                            <i class="bi bi-hash me-2"></i>TOTAL HOG
                                        </label>
                                        <input type="number" id="total_hog" name="total_hog" class="form-control no-spinner" 
                                               placeholder="Heads" min="1" required>
                                        @error('total_hog')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- INVESTMENT DATE -->
                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="investment_date" class="form-label">
                                            <i class="bi bi-calendar-event me-2"></i>INVESTMENT DATE
                                        </label>
                                        @php
                                            $oldInvestmentDate = old('investment_date');
                                            if ($oldInvestmentDate && preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $oldInvestmentDate, $matches)) {
                                                $oldInvestmentDate = $matches[3] . '-' . str_pad($matches[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                                            }
                                        @endphp
                                        <input
                                            type="date"
                                            id="investment_date"
                                            name="investment_date"
                                            class="form-control date-input"
                                            value="{{ $oldInvestmentDate ?: now()->toDateString() }}"
                                            required
                                        >
                                        @error('investment_date')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="d-flex gap-3 mt-5">
                                    <button type="submit" class="btn btn-primary grow">
                                        <i class="bi bi-plus me-2"></i>Create Investment
                                    </button>
                                    <a href="{{ route('investments.index') }}" class="btn btn-secondary grow">
                                        Cancel
                                    </a>
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
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--pt-text);
        }

        .investment-form .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--pt-muted);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }

        .investment-form .form-control,
        .investment-form .form-select {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            color: var(--pt-text);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .investment-form .form-select {
            padding-right: 2.5rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23666666' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        .investment-form .form-control::placeholder {
            color: var(--pt-muted);
        }

        .investment-form .form-control:focus,
        .investment-form .form-select:focus {
            background: var(--pt-surface);
            border-color: #5b8def;
            color: var(--pt-text);
            box-shadow: 0 0 0 3px rgba(91, 141, 239, 0.1);
        }

        .investment-form .input-group-text {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            color: var(--pt-muted);
            font-weight: 600;
        }

        .btn-primary {
            background: #1a1a1a;
            border: 1.5px solid transparent;
            color: #ffffff;
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            background: #2d2d2d;
            color: #ffffff;
            border-color: #ffffff;
        }

        :root[data-theme="dark"] .btn-primary {
            background: #ffffff;
            border: 1.5px solid transparent;
            color: #1a1a1a;
            box-shadow: 0 2px 8px rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        :root[data-theme="dark"] .btn-primary:hover {
            background: #f5f5f5;
            color: #1a1a1a;
            border-color: #1a1a1a;
        }

        .btn-secondary {
            background: transparent;
            border: 1.5px solid var(--pt-text);
            color: var(--pt-text);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: var(--pt-surface-soft);
            color: var(--pt-text);
            border-color: var(--pt-text);
        }

        :root[data-theme="dark"] .btn-secondary {
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.6);
            color: var(--pt-text);
            transition: all 0.3s ease;
        }

        :root[data-theme="dark"] .btn-secondary:hover {
            background: var(--pt-surface-soft);
            border-color: #ffffff;
            color: var(--pt-text);
        }

        .text-danger {
            color: var(--pt-accent);
        }

        :root[data-theme="dark"] .form-control,
        :root[data-theme="dark"] .form-select {
            background-color: var(--pt-surface-soft) !important;
            border-color: var(--pt-border) !important;
            color: var(--pt-text) !important;
        }

        :root[data-theme="dark"] .form-select {
            background-color: var(--pt-surface-soft) !important;
            border-color: var(--pt-border) !important;
            color: var(--pt-text) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%239cb0c9' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 16px 12px !important;
            padding-right: 2.5rem !important;
        }

        :root[data-theme="dark"] .form-control::placeholder {
            color: var(--pt-muted);
        }

        :root[data-theme="dark"] .form-control:focus,
        :root[data-theme="dark"] .form-select:focus {
            background-color: var(--pt-surface) !important;
            border-color: #5b8def !important;
            color: var(--pt-text) !important;
        }

        :root[data-theme="dark"] .input-group-text {
            background-color: transparent !important;
            border-color: transparent !important;
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .investment-form .input-group {
            border-color: var(--pt-border) !important;
        }

        :root[data-theme="dark"] .investment-form .input-group:focus-within {
            border-color: #5b8def !important;
            box-shadow: 0 0 0 0.2rem rgba(91, 141, 239, 0.15) !important;
        }

        /* ROI Input Styling */
        .roi-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .roi-input {
            flex: 1;
            padding-right: 2rem !important;
        }

        .roi-percent {
            position: absolute;
            right: 0.75rem;
            font-weight: 600;
            color: var(--pt-text);
            font-size: 0.9375rem;
            pointer-events: none;
        }

        :root[data-theme="dark"] .roi-percent {
            color: var(--pt-text) !important;
        }

        .roi-input:focus + .roi-percent {
            color: #5b8def;
        }

        /* Hide ROI percent when input is empty */
        .roi-input:placeholder-shown + .roi-percent {
            opacity: 0.5;
        }

        /* Remove number spinner arrows */
        .investment-form .no-spinner::-webkit-outer-spin-button,
        .investment-form .no-spinner::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
            display: none;
        }

        .investment-form .no-spinner[type=number] {
            -moz-appearance: textfield;
        }

        /* Input group styling */
        .investment-form .input-group {
            border: 1px solid var(--pt-border);
            border-radius: 0.375rem;
            overflow: hidden;
            transition: all 0.2s ease;
            display: flex;
            align-items: stretch;
        }

        .investment-form .input-group:focus-within {
            border-color: #5b8def;
            box-shadow: 0 0 0 0.2rem rgba(91, 141, 239, 0.15);
        }

        .investment-form .input-group-text {
            background-color: transparent;
            border: none;
            font-weight: 600;
            padding: 0.75rem 0.75rem;
            color: var(--pt-text);
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 50px;
        }

        .investment-form .input-group .form-control {
            border: none;
            padding-left: 0.5rem;
        }

        .investment-form .input-group .form-control:focus {
            border: none;
            box-shadow: none;
            outline: none;
        }

        :root[data-theme="dark"] .investment-form .input-group-text {
            color: var(--pt-text);
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem !important;
            }

            .section-heading {
                font-size: 1.25rem;
            }
        }

        /* Date input wrapper styling */
        .date-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .date-input {
            flex: 1;
            padding-right: 2.5rem !important;
        }

        .investment-form input[type="date"].date-input {
            cursor: pointer;
        }

        .investment-form input[type="date"].date-input::-webkit-calendar-picker-indicator {
            cursor: pointer;
        }

        :root[data-theme="dark"] .investment-form input[type="date"].date-input::-webkit-calendar-picker-indicator {
            filter: invert(1) brightness(1.2);
            opacity: 0.95;
        }

        .date-picker-btn {
            position: absolute;
            right: 0.75rem;
            background: transparent;
            border: none;
            color: var(--pt-muted);
            cursor: pointer;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            z-index: 10;
            pointer-events: auto;
        }

        .date-picker-btn:hover {
            color: var(--pt-muted);
        }

        .view-calendar-btn {
            display: none;
        }







        /* Raiser Select Avatar Styles */
        .raiser-select-display {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.375rem 0.75rem;
            background-color: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .raiser-select-display:hover {
            border-color: #5b8def;
            box-shadow: 0 0 0 0.2rem rgba(91, 141, 239, 0.15);
        }

        .raiser-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #5b8def, #3a7ee0);
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .raiser-name {
            flex: 1;
            color: var(--pt-text);
            font-weight: 500;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .raiser-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .raiser-dropdown.show {
            display: block;
        }

        .raiser-option {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .raiser-option:hover {
            background-color: rgba(91, 141, 239, 0.08);
        }

        .raiser-option.selected {
            background-color: rgba(91, 141, 239, 0.14);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--pt-text);
            margin-bottom: 1.5rem;
        }
    </style>

    <script>
        // Initialize Raiser Select with Avatars
        document.addEventListener('DOMContentLoaded', function() {
            const raiserSelect = document.getElementById('raiser_id');
            
            if (raiserSelect) {
                function getInitials(name) {
                    return name.split(' ').map(word => word[0]).join('').toUpperCase();
                }

                function updateRaiserDisplay() {
                    const selectedOption = raiserSelect.options[raiserSelect.selectedIndex];
                    const selectedValue = selectedOption.value;
                    const selectedText = selectedOption.text;
                    const initials = selectedOption.dataset.initials || getInitials(selectedText);

                    if (selectedValue) {
                        raiserSelect.style.display = 'none';
                        
                        let display = document.querySelector('.raiser-select-display');
                        if (!display) {
                            display = document.createElement('div');
                            display.className = 'raiser-select-display';
                            raiserSelect.parentNode.insertBefore(display, raiserSelect);
                        }

                        display.innerHTML = `
                            <div class="raiser-avatar">${initials}</div>
                            <div class="raiser-name">${selectedText}</div>
                        `;

                        display.addEventListener('click', toggleDropdown);
                    } else {
                        raiserSelect.style.display = 'block';
                        const display = document.querySelector('.raiser-select-display');
                        if (display) display.remove();
                    }
                }

                function toggleDropdown() {
                    const display = document.querySelector('.raiser-select-display');
                    let dropdown = display.querySelector('.raiser-dropdown');
                    
                    if (!dropdown) {
                        dropdown = document.createElement('div');
                        dropdown.className = 'raiser-dropdown';
                        display.appendChild(dropdown);
                        
                        populateDropdown(dropdown);
                    }
                    
                    dropdown.classList.toggle('show');
                }

                function populateDropdown(dropdown) {
                    const options = Array.from(raiserSelect.options).slice(1);
                    
                    dropdown.innerHTML = options.map(option => {
                        const initials = option.dataset.initials || getInitials(option.text);
                        return `
                            <div class="raiser-option" data-value="${option.value}" data-pig-type="${option.dataset.pigType}">
                                <div class="raiser-avatar">${initials}</div>
                                <span>${option.text}</span>
                            </div>
                        `;
                    }).join('');

                    dropdown.querySelectorAll('.raiser-option').forEach(item => {
                        item.addEventListener('click', function() {
                            raiserSelect.value = this.dataset.value;
                            
                            // Auto-populate hog type
                            const pigType = this.dataset.pigType;
                            const hogTypeDisplay = document.getElementById('hog_type_display');
                            const hogTypeInput = document.getElementById('hog_type');
                            if (pigType && hogTypeDisplay && hogTypeInput) {
                                const displayValue = pigType.charAt(0).toUpperCase() + pigType.slice(1);
                                hogTypeDisplay.value = displayValue;
                                hogTypeInput.value = pigType.toLowerCase();
                            }
                            
                            updateRaiserDisplay();
                            dropdown.classList.remove('show');
                        });
                    });
                }

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    const dropdown = document.querySelector('.raiser-dropdown');
                    const display = document.querySelector('.raiser-select-display');
                    
                    if (dropdown && !dropdown.contains(e.target) && !display?.contains(e.target)) {
                        dropdown.classList.remove('show');
                    }
                });

                raiserSelect.addEventListener('change', updateRaiserDisplay);
                
                // Auto-populate hog type when raiser is selected
                raiserSelect.addEventListener('change', function() {
                    const selectedOption = raiserSelect.options[raiserSelect.selectedIndex];
                    const pigType = selectedOption.dataset.pigType;
                    const hogTypeDisplay = document.getElementById('hog_type_display');
                    const hogTypeInput = document.getElementById('hog_type');
                    
                    if (pigType && hogTypeDisplay && hogTypeInput) {
                        const displayValue = pigType.charAt(0).toUpperCase() + pigType.slice(1);
                        hogTypeDisplay.value = displayValue;
                        hogTypeInput.value = pigType.toLowerCase();
                    }
                });
                
                updateRaiserDisplay();
            }
        });
    </script>
@endsection
