@extends('layouts.admin')

@section('content')
    <div class="card dashboard-bootstrap-card">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <p class="section-label mb-2">Raiser Profile</p>
                    <h2 class="section-heading mb-0">{{ $raiser->name }}</h2>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('raisers.edit', $raiser->id) }}" class="btn btn-dark">Edit Raiser</a>
                    <a href="{{ route('raisers.index') }}" class="btn btn-outline-secondary">Back to Directory</a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                            <h3 class="table-name h4 mb-1">{{ $raiser->name }}</h3>
                            <span class="badge rounded-pill status-badge {{ strtolower($raiser->status) === 'active' ? 'status-badge-active' : 'status-badge-inactive' }}">
                                {{ $raiser->status }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card h-100"><div class="card-body"><div class="table-meta">Contact Person</div><div class="table-name">{{ $raiser->contact_person }}</div></div></div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100"><div class="card-body"><div class="table-meta">Phone</div><div class="table-name">{{ $raiser->phone }}</div></div></div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100"><div class="card-body"><div class="table-meta">Email</div><div class="table-name">{{ $raiser->email }}</div></div></div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100"><div class="card-body"><div class="table-meta">Location</div><div class="table-name">{{ $raiser->location }}</div></div></div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100"><div class="card-body"><div class="table-meta">Active Batch</div><div class="table-name">{{ $raiser->batch }}</div></div></div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100"><div class="card-body"><div class="table-meta">Type of Pig</div><div class="table-name">{{ $raiser->pig_type }}</div></div></div>
                        </div>
                        <div class="col-12">
                            <div class="card h-100"><div class="card-body"><div class="table-meta">Address</div><div class="table-name">{{ $raiser->address }}</div></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
