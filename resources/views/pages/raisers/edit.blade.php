@extends('layouts.admin')

@section('content')
    <div class="card dashboard-bootstrap-card">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <p class="section-label mb-2">Edit Raiser</p>
                    <h2 class="section-heading mb-0">{{ $raiser->name }}</h2>
                </div>
                <a href="{{ route('raisers.show', $raiser->id) }}" class="btn btn-outline-secondary">View Profile</a>
            </div>

            @if (session('status'))
                <div class="alert alert-info">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('raisers.update', $raiser->id) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-4">
                    <label class="form-label">Raiser Code</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $raiser->code) }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Raiser Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $raiser->name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $raiser->contact_person) }}">
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
                    <label class="form-label">Active Batch</label>
                    <input type="text" name="batch" class="form-control" value="{{ old('batch', $raiser->batch) }}">
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
@endsection
