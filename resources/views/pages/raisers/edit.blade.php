@extends('layouts.admin')

@section('content')
    <div class="card dashboard-bootstrap-card">
        <div class="card-body">
            <h1 class="page-title mb-4">Edit Raiser</h1>
            @if (session('status'))
                <div class="alert alert-info">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('raisers.update', $raiser->id) }}" class="row g-3">
                @csrf
                @method('PUT')

            <div class="col-12">
                    <label class="form-label">Raiser Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $raiser->name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $raiser->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $raiser->email) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $raiser->location) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type of Pig</label>
                    <input type="text" name="pig_type" class="form-control" value="{{ old('pig_type', $raiser->pig_type) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach (['Active', 'Inactive'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $raiser->status) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $raiser->address) }}">
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Save Changes</button>
                    <a href="{{ route('raisers.index') }}" class="btn btn-outline-secondary">Back to Directory</a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .page-title {
            color: var(--pt-text);
            font-size: 2rem;
            font-weight: 700;
        }

        :root[data-theme="dark"] .page-title {
            color: #ffffff;
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
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--pt-text);
            margin: 0;
        }

        .alert {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 1rem;
            border-radius: 8px;
        }

        :root[data-theme="dark"] .alert {
            background-color: rgba(23, 162, 184, 0.15);
            border-color: rgba(23, 162, 184, 0.3);
            color: #87ceeb;
        }

        .form-label {
            font-weight: 600;
            color: var(--pt-text);
        }

        .form-control,
        .form-select {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            color: var(--pt-text);
            transition: all 0.2s ease;
        }

        :root[data-theme="dark"] .form-control,
        :root[data-theme="dark"] .form-select {
            background: #1b2638;
            border-color: #28354a;
            color: #ecf2ff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--pt-accent);
            box-shadow: 0 0 0 3px rgba(239, 91, 108, 0.1);
            background-color: var(--pt-surface);
        }

        :root[data-theme="dark"] .form-control:focus,
        :root[data-theme="dark"] .form-select:focus {
            background-color: #1b2638;
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

        .btn-dark {
            background: #0f1f33;
            border: 1px solid transparent;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .btn-dark:hover {
            background: #1a2d42;
            color: #ffffff;
        }

        :root[data-theme="dark"] .btn-dark {
            background: #ffffff;
            color: #0f1f33;
        }

        :root[data-theme="dark"] .btn-dark:hover {
            background: #f0f0f0;
            color: #0f1f33;
        }

        .btn-outline-secondary {
            background: transparent;
            border: 1px solid var(--pt-border);
            color: var(--pt-text);
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: var(--pt-surface-soft);
            border-color: var(--pt-text);
        }
    </style>
@endsection
