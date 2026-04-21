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
                            <h1 class="page-title mb-5">Create New Raiser</h1>

                            <form method="POST" action="{{ route('raisers.store') }}" class="raiser-form">
                                @csrf

                                <!-- HOG RAISER NAME -->
                                <div class="mb-4">
                                    <label for="name" class="form-label">
                                        <i class="bi bi-person-fill me-2"></i>HOG RAISER NAME
                                    </label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter name" required>
                                    @error('name')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Two Column Layout -->
                                <div class="row">
                                    <!-- PHONE -->
                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="phone" class="form-label">
                                            <i class="bi bi-telephone-fill me-2"></i>PHONE
                                        </label>
                                        <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+63 XXX XXX XXXX" required>
                                        @error('phone')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- EMAIL -->
                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="email" class="form-label">
                                            <i class="bi bi-envelope-fill me-2"></i>EMAIL
                                        </label>
                                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="official@raiser-domain.com" required>
                                        @error('email')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- ADDRESS -->
                                <div class="mb-4">
                                    <label for="address" class="form-label">
                                        <i class="bi bi-geo-alt-fill me-2"></i>ADDRESS
                                    </label>
                                    <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" placeholder="e.g., Malasiqui, San Carlos" required>
                                    @error('address')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Two Column Layout -->
                                <div class="row">
                                    <!-- PIG TYPE -->
                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="pig_type_id" class="form-label">
                                            <i class="bi bi-dice-4-fill me-2"></i>SELECT PIG
                                        </label>
                                        <select id="pig_type_id" name="pig_type_id" class="form-select @error('pig_type_id') is-invalid @enderror" required>
                                            <option value="">Select breed type</option>
                                            @foreach(($pigTypes ?? collect()) as $pigType)
                                                <option value="{{ $pigType->id }}" @selected(old('pig_type_id') == $pigType->id)>{{ $pigType->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('pig_type_id')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- STATUS -->
                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="status" class="form-label">
                                            <i class="bi bi-check-circle-fill me-2"></i>STATUS
                                        </label>
                                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="Active" @selected(old('status', 'Active') === 'Active')>Active</option>
                                            <option value="Inactive" @selected(old('status') === 'Inactive')>Inactive</option>
                                            <option value="Suspended" @selected(old('status') === 'Suspended')>Suspended</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="d-flex gap-3 mt-5">
                                    <button type="submit" class="btn btn-primary grow">
                                        <i class="bi bi-plus-lg"></i> Create New Account
                                    </button>
                                    <a href="{{ route('raisers.index') }}" class="btn btn-secondary grow">Cancel</a>
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

        .raiser-form .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--pt-muted);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }

        .raiser-form .form-control,
        .raiser-form .form-select {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            color: var(--pt-text);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .raiser-form .form-select {
            padding-right: 2.5rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23666666' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        .raiser-form .form-control::placeholder {
            color: var(--pt-muted);
        }

        .raiser-form .form-control:focus,
        .raiser-form .form-select:focus {
            background: var(--pt-surface);
            border-color: #5b8def;
            color: var(--pt-text);
            box-shadow: 0 0 0 3px rgba(91, 141, 239, 0.1);
        }

        .raiser-form .input-group-text {
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

        :root[data-theme="dark"] .raiser-form .input-group {
            border-color: var(--pt-border) !important;
        }

        :root[data-theme="dark"] .raiser-form .input-group:focus-within {
            border-color: #5b8def !important;
            box-shadow: 0 0 0 0.2rem rgba(91, 141, 239, 0.15) !important;
        }

        /* Remove number spinner arrows */
        .raiser-form .no-spinner::-webkit-outer-spin-button,
        .raiser-form .no-spinner::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
            display: none;
        }

        .raiser-form .no-spinner[type=number] {
            -moz-appearance: textfield;
        }

        /* Input group styling */
        .raiser-form .input-group {
            border: 1px solid var(--pt-border);
            border-radius: 0.375rem;
            overflow: hidden;
            transition: all 0.2s ease;
            display: flex;
            align-items: stretch;
        }

        .raiser-form .input-group:focus-within {
            border-color: #5b8def;
            box-shadow: 0 0 0 0.2rem rgba(91, 141, 239, 0.15);
        }

        .raiser-form .input-group-text {
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

        .raiser-form .input-group .form-control {
            border: none;
            padding-left: 0.5rem;
        }

        .raiser-form .input-group .form-control:focus {
            border: none;
            box-shadow: none;
            outline: none;
        }

        :root[data-theme="dark"] .raiser-form .input-group-text {
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

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--pt-text);
            margin-bottom: 1.5rem;
        }

        .grow {
            transition: transform 0.2s ease;
        }

        .grow:hover {
            transform: scale(1.02);
        }
    </style>
@endsection
