@extends('layouts.admin')

@section('content')
    <div class="card dashboard-bootstrap-card">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
                <a href="{{ route('raisers.create') }}" class="btn btn-dark">Create New Account</a>
            </div>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="GET" action="{{ route('raisers.index') }}" class="row g-3 mb-4">
                <div class="col-12 col-lg-10">
                    <div class="input-group search-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="q" value="{{ $query }}" class="form-control border-start-0" placeholder="Search by name, code, location, batch, or type">
                    </div>
                </div>
                <div class="col-12 col-lg-2">
                    <button type="submit" class="btn btn-dark w-100">Search</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th>Raiser Profile</th>
                            <th>Brgy</th>
                            <th>Active Batch</th>
                            <th>Type of Pig</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($raisers as $raiser)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="raiser-avatar avatar-{{ $raiser->accent }}">{{ $raiser->initials }}</div>
                                        <div>
                                            <div class="table-name">{{ $raiser->name }}</div>
                                            <div class="table-meta">ID: {{ $raiser->code }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $raiser->location }}</td>
                                <td><span class="{{ $raiser->status === 'Active' ? 'text-danger fw-bold' : 'text-danger fw-semibold' }}">{{ $raiser->batch }}</span></td>
                                <td>{{ $raiser->pig_type }}</td>
                                <td>
                                    <span class="badge rounded-pill status-badge {{ strtolower($raiser->status) === 'active' ? 'status-badge-active' : 'status-badge-inactive' }}">
                                        {{ $raiser->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('raisers.show', $raiser->id) }}" class="btn btn-sm table-icon-btn" aria-label="View {{ $raiser->name }}">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('raisers.edit', $raiser->id) }}" class="btn btn-sm table-icon-btn" aria-label="Edit {{ $raiser->name }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No raisers matched your search.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
