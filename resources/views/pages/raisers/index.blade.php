@extends('layouts.admin')

@php
    $hideTopbarTitle = true;
@endphp

@section('content')
    <section class="bootstrap-dashboard">
        <div class="dashboard-stage">
            <h1 class="page-title mb-5">Hog Raiser</h1>
            <div class="card dashboard-bootstrap-card">
                <div class="card-body">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                        <a href="{{ route('raisers.create') }}" class="btn btn-dark">
                            <i class="bi bi-person-plus me-2"></i>Create New Account
                        </a>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <x-search-bar
                        action="{{ route('raisers.index') }}"
                        method="GET"
                        input-name="q"
                        input-value="{{ $query }}"
                        placeholder="Search raisers..."
                        button-label="Search"
                        class="mb-4"
                    />

                    <div class="table-responsive">
                        <table class="table align-middle dashboard-table mb-0">
                            <thead>
                                <tr>
                                    <th>HOG RAISER</th>
                                    <th>ADDRESS</th>
                                    <th>PHONE NUMBER</th>
                                    <th>PIG TYPE</th>
                                    <th>STATUS</th>
                                    <th class="text-end">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($raisers as $raiser)
                                    <tr>
                                        <td>
                                            <div class="table-name">{{ $raiser->name }}</div>
                                        </td>
                                        <td>
                                            <div class="table-copy">{{ $raiser->address }}</div>
                                        </td>
                                        <td>
                                            <div class="table-copy">{{ $raiser->phone ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="table-copy">{{ $raiser->pigType?->name ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill status-badge {{ strtolower($raiser->status) === 'active' ? 'status-badge-active' : 'status-badge-inactive' }}">
                                                {{ ucfirst($raiser->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-3">
                                                <a href="{{ route('raisers.show', $raiser->id) }}" class="table-icon-btn" aria-label="View {{ $raiser->name }}" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('raisers.edit', $raiser->id) }}" class="table-icon-btn" aria-label="Edit {{ $raiser->name }}" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 raiser-empty-state">No raiser found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </section>

    <script>
        // Client-side raiser search
        function performRaiserSearch() {
            const searchTerm = document.querySelector('.search-input').value.toLowerCase();
            const tableRows = document.querySelectorAll('.dashboard-table tbody tr');
            let visibleCount = 0;

            tableRows.forEach(row => {
                // Skip empty state row
                if (row.querySelector('.raiser-empty-state')) {
                    return;
                }
                
                const text = row.textContent.toLowerCase();
                const isVisible = text.includes(searchTerm);
                row.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });

            // Show/hide empty state
            const emptyStateRow = document.querySelector('.dashboard-table tbody tr .raiser-empty-state')?.closest('tr');
            if (emptyStateRow) {
                emptyStateRow.style.display = visibleCount === 0 ? '' : 'none';
            }
        }

        // Prevent form submission and use client-side filtering instead
        document.querySelector('.search-wrapper').addEventListener('submit', function(e) {
            e.preventDefault();
            performRaiserSearch();
        });

        // Reset to main content when search input is cleared
        document.querySelector('.search-input').addEventListener('input', function(e) {
            if (e.target.value === '') {
                const tableRows = document.querySelectorAll('.dashboard-table tbody tr');
                tableRows.forEach(row => {
                    row.style.display = '';
                });
            }
        });
    </script>

    <style>
        .section-heading {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--pt-text);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            border: 1.5px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9375rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-dark {
            background: #1a1a1a;
            color: #ffffff;
        }

        .btn-dark:hover {
            background: #2d2d2d;
            border-color: #ffffff;
        }

        :root[data-theme="dark"] .btn-dark {
            background: #ffffff;
            color: #1a1a1a;
        }

        :root[data-theme="dark"] .btn-dark:hover {
            background: #f5f5f5;
            border-color: #1a1a1a;
        }

        .table-copy {
            color: var(--pt-muted);
            font-size: 0.9375rem;
        }

        .raiser-count-text {
            color: var(--pt-text);
        }

        .raiser-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            color: white;
            background: #5b8def;
        }

        .raiser-avatar.avatar-default {
            background: #5b8def;
        }

        .raiser-avatar.avatar-primary {
            background: #5b8def;
        }

        .raiser-avatar.avatar-accent {
            background: #ef5b6c;
        }

        .raiser-avatar.avatar-success {
            background: #43cb89;
        }

        .table-icon-btn {
            color: var(--pt-text);
            text-decoration: none;
            padding: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .table-icon-btn:hover {
            background: var(--pt-surface-soft);
            color: var(--pt-accent);
        }

        .raiser-empty-state {
            color: var(--pt-text);
            font-weight: 600;
        }

        :root[data-theme="dark"] .raiser-empty-state {
            color: #ecf2ff;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--pt-text);
            margin-bottom: 1.5rem;
        }
    </style>
@endsection
