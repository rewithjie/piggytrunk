@extends('layouts.admin')

@php
    $hideTopbarTitle = true;
@endphp

@section('content')
    <section class="bootstrap-dashboard">
        <div class="dashboard-stage">
            <h1 class="page-title mb-5"><i class="bi bi-plus-circle me-2"></i>Create New Raiser</h1>
            <div class="create-raiser-container">
                <form method="POST" action="{{ route('raisers.store') }}" class="create-raiser-form">
                    @csrf

                    <!-- Hog Raiser Name + Phone -->
                    <div class="form-row-two">
                        <div class="form-group">
                            <label class="form-label">Hog Raiser Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter legal entity name" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+63 XXX XXX XXXX">
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="official@raiser-domain.com">
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" placeholder="Complete facility or residential address">
                        @error('location')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Select Pig + Status -->
                    <div class="form-row-two">
                        <div class="form-group">
                            <label class="form-label">Select Pig</label>
                            <select name="pig_type" class="form-select @error('pig_type') is-invalid @enderror" required>
                                <option value="">Select breed type</option>
                                <option value="Sow" @selected(old('pig_type') === 'Sow')>Sow</option>
                                <option value="Piglet" @selected(old('pig_type') === 'Piglet')>Piglet</option>
                                <option value="Fattening" @selected(old('pig_type') === 'Fattening')>Fattening</option>
                                <option value="Boar" @selected(old('pig_type') === 'Boar')>Boar</option>
                            </select>
                            @error('pig_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="Active" @selected(old('status', 'Active') === 'Active')>Active</option>
                                <option value="Inactive" @selected(old('status') === 'Inactive')>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-light">Create New Account</button>
                        <a href="{{ route('raisers.index') }}" class="btn btn-outline-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <style>
        .create-raiser-container {
            max-width: 900px;
            overflow: visible;
        }

        .create-raiser-header {
            border-bottom: 1px solid var(--pt-text);
            padding-bottom: 2rem;
        }

        .section-label {
            font-size: 0.75rem;
            letter-spacing: 0.15em;
            font-weight: 700;
            color: var(--pt-muted);
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .section-heading {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--pt-text);
            margin: 0;
        }

        .create-raiser-form {
            margin-top: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-row-two {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 0;
        }

        .form-label {
            display: block;
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--pt-text);
            margin-bottom: 0.75rem;
        }

        .form-control,
        .form-select {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            color: var(--pt-text);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.9375rem;
            transition: all 0.2s ease;
            width: 100%;
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px;
            padding-right: 2.5rem;
            z-index: 10;
            position: relative;
        }

        .form-select:focus {
            z-index: 20;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: var(--pt-surface);
            border-color: var(--pt-accent);
            color: var(--pt-text);
            box-shadow: 0 0 0 3px rgba(239, 91, 108, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--pt-muted);
            opacity: 0.6;
        }

        .form-select option {
            background: var(--pt-surface);
            color: var(--pt-text);
            padding: 8px;
        }

        .form-select option:checked {
            background: var(--pt-accent);
            color: white;
        }

        :root[data-theme="dark"] .form-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3E%3C/svg%3E");
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid var(--pt-border);
        }

        .btn {
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9375rem;
        }

        .btn-light {
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

        .btn-light:hover {
            background: #2d2d2d;
            color: #ffffff;
            border-color: #ffffff;
        }

        :root[data-theme="dark"] .btn-light {
            background: #ffffff;
            border: 1.5px solid transparent;
            color: #1a1a1a;
            box-shadow: 0 2px 8px rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        :root[data-theme="dark"] .btn-light:hover {
            background: #f5f5f5;
            color: #1a1a1a;
            border-color: #1a1a1a;
        }

        .btn-outline-light {
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

        .btn-outline-light:hover {
            background: var(--pt-surface-soft);
            border-color: var(--pt-text);
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .btn-outline-light {
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.6);
            color: var(--pt-text);
            transition: all 0.3s ease;
        }

        :root[data-theme="dark"] .btn-outline-light:hover {
            background: var(--pt-surface-soft);
            border-color: #ffffff;
            color: var(--pt-text);
        }

        @media (max-width: 768px) {
            .form-row-two {
                grid-template-columns: 1fr;
            }

            .section-heading {
                font-size: 1.75rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions button,
            .form-actions a {
                width: 100%;
            }
        }
    </style>
@endsection
