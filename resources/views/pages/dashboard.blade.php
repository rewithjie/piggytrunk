@extends('layouts.admin')

@section('content')
    <section class="bootstrap-dashboard">
        <div class="dashboard-stage">
            @foreach ($raisers as $raiser)
                <div class="lifecycle-raiser-card mb-5">
                    <!-- Raiser Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <h2 class="lifecycle-raiser-name mb-0">{{ $raiser->name }}</h2>
                        <div class="d-flex gap-2">
                            <a href="{{ route('raisers.download-report', $raiser->id) }}" class="btn btn-sm btn-outline-secondary" download>
                                <i class="bi bi-download me-1"></i>Download Report
                            </a>
                            <a href="{{ route('raisers.show', $raiser->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-person me-1"></i>View Profile
                            </a>
                        </div>
                    </div>

                    <!-- Lifecycle Categories -->
                    @foreach ($lifecycles as $categoryName => $stages)
                        <div class="lifecycle-category mb-5">
                            <h4 class="lifecycle-category-title text-uppercase mb-4">{{ $categoryName }}</h4>
                            
                            <div class="lifecycle-timeline">
                                <!-- Timeline Line -->
                                <div class="timeline-line"></div>
                                
                                <!-- Stages -->
                                <div class="timeline-stages">
                                    @foreach ($stages as $index => $stage)
                                        @php
                                            $isCompleted = $stage['status'] === 'completed';
                                            $isInProgress = $stage['status'] === 'in-progress';
                                            $stageClass = $isCompleted ? 'completed' : ($isInProgress ? 'in-progress' : 'pending');
                                        @endphp
                                        
                                        <div class="timeline-stage stage-{{ $stageClass }}">
                                            <div class="stage-node">
                                                @if ($isCompleted)
                                                    <i class="bi bi-check-lg"></i>
                                                @elseif ($isInProgress)
                                                    <span class="progress-dot"></span>
                                                @endif
                                            </div>
                                            <div class="stage-label">
                                                <div class="stage-title">{{ $stage['label'] }}</div>
                                                <div class="stage-duration">{{ $stage['duration'] }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

    <style>
        .lifecycle-raiser-card {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 12px;
            padding: 2rem;
        }

        .lifecycle-raiser-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--pt-text);
        }

        .lifecycle-category {
            margin-left: 0;
        }

        .lifecycle-category-title {
            font-size: 0.75rem;
            letter-spacing: 0.15em;
            font-weight: 700;
            color: var(--pt-muted);
            margin-bottom: 1.5rem;
        }

        .lifecycle-timeline {
            position: relative;
            padding: 1rem 0;
        }

        .timeline-line {
            position: absolute;
            top: 1.5rem;
            left: 2rem;
            right: 0;
            height: 2px;
            background: var(--pt-border);
            z-index: 0;
        }

        .timeline-stages {
            display: flex;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }

        .timeline-stage {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }

        .stage-node {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            font-weight: 600;
            font-size: 1.25rem;
            background: var(--pt-surface);
            border: 2px solid var(--pt-border);
            position: relative;
            z-index: 2;
        }

        .timeline-stage.stage-completed .stage-node {
            background: var(--pt-success);
            border-color: var(--pt-success);
            color: white;
        }

        .timeline-stage.stage-in-progress .stage-node {
            background: var(--pt-in-progress);
            border-color: var(--pt-in-progress);
            color: white;
            animation: pulse-stage 2s infinite;
        }

        .timeline-stage.stage-pending .stage-node {
            background: var(--pt-surface-soft);
            border-color: var(--pt-pending-border);
        }

        .progress-dot {
            display: inline-block;
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            background: currentColor;
        }

        .stage-label {
            text-align: center;
            flex: 1;
            max-width: 100px;
        }

        .stage-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--pt-text);
            margin-bottom: 0.25rem;
        }

        .stage-duration {
            font-size: 0.75rem;
            color: var(--pt-muted);
        }

        @keyframes pulse-stage {
            0%, 100% {
                box-shadow: 0 0 0 0 color-mix(in srgb, var(--pt-in-progress) 70%, transparent);
            }
            50% {
                box-shadow: 0 0 0 6px color-mix(in srgb, var(--pt-in-progress) 0%, transparent);
            }
        }

        @media (max-width: 768px) {
            .timeline-stages {
                flex-wrap: wrap;
                gap: 1rem;
            }

            .timeline-stage {
                flex: 0 0 calc(50% - 0.5rem);
            }

            .timeline-line {
                display: none;
            }

            .stage-node {
                margin-bottom: 0.5rem;
            }
        }
    </style>
@endsection
