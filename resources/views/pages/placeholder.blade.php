@extends('layouts.admin')

@section('content')
    <div class="dashboard-card detail-card text-center py-5">
        <p class="section-label mb-2">{{ $section }}</p>
        <h2 class="panel-title mb-3">{{ $title }}</h2>
        <p class="text-muted mx-auto placeholder-copy">
            This section is connected and clickable already. We can build the full {{ strtolower($title) }} module next without changing the overall project structure.
        </p>
        <a href="{{ route('dashboard') }}" class="btn btn-dark mt-2">Back to Dashboard</a>
    </div>
@endsection
