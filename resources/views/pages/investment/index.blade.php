@extends('layouts.admin')

@php
    $hideTopbarTitle = true;
@endphp

@section('content')
    <section class="bootstrap-dashboard">
        <div class="dashboard-stage">
            <h1 class="page-title mb-5">Investment</h1>
            <div class="row g-4 align-items-stretch justify-content-center">
                <!-- Main Investment Table -->
                <div class="col-12 col-xl-10">
                    <div class="card dashboard-bootstrap-card h-100">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                                <a href="{{ route('investments.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus me-2"></i>New Investment
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle investment-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>HOG RAISER</th>
                                            <th>INITIAL CAPITAL</th>
                                            <th>HOG TYPE</th>
                                            <th>TOTAL HOG</th>
                                            <th>INVESTMENT DATE</th>
                                            <th class="text-center">STAGE</th>
                                            <th class="text-center">ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($investments as $index => $investment)
                                            @php
                                                $batch = $investment->batch;
                                                $raiser = $batch?->raiser;
                                                $pigType = $batch?->pigType;
                                                $capitalDisplay = '₱ ' . number_format($investment->total_amount, 0);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="investment-raiser-name">{{ $raiser?->name ?? 'Unknown' }}</div>
                                                </td>
                                                <td>
                                                    <span class="investment-capital">{{ $capitalDisplay }}</span>
                                                </td>
                                                <td>
                                                    <span class="investment-type">{{ $pigType?->name ?? 'Unknown' }}</span>
                                                </td>
                                                <td>
                                                    <span class="investment-count">{{ $batch?->current_quantity ?? 0 }}</span>
                                                </td>
                                                <td>
                                                    <span class="investment-date">{{ $investment->investment_date ? $investment->investment_date->format('m/d/Y') : 'N/A' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-stage stage-{{ strtolower($investment->status) }}">
                                                        {{ strtoupper($investment->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('investments.show', ['raiser' => $raiser?->id ?? '#']) }}" class="btn-action-icon" title="View Investment">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">No investments found. <br><a href="{{ route('investments.create') }}" class="btn btn-sm btn-primary mt-3">Create First Investment</a></td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
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

        .section-icon {
            font-size: 1.5rem;
            color: var(--pt-muted);
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

        .investment-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .investment-table thead th {
            background: transparent;
            border-bottom: 1px solid var(--pt-border);
            color: var(--pt-muted);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem;
        }

        .investment-table tbody tr {
            background: var(--pt-surface);
            border-bottom: 1px solid var(--pt-border);
            transition: background-color 0.2s ease;
        }

        .investment-table tbody tr:hover {
            background-color: var(--pt-surface-soft);
        }

        .investment-table tbody td {
            padding: 1rem;
            vertical-align: middle;
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .investment-table {
            background-color: #151f2e !important;
            color: #ecf2ff !important;
        }

        :root[data-theme="dark"] .investment-table tbody tr {
            background-color: #151f2e !important;
        }

        :root[data-theme="dark"] .investment-table tbody td {
            background-color: #151f2e !important;
            color: #ecf2ff !important;
        }

        :root[data-theme="dark"] .investment-table thead th {
            background-color: #0f1724 !important;
            color: #9cb0c9 !important;
        }

        :root[data-theme="dark"] .investment-raiser-name,
        :root[data-theme="dark"] .investment-raiser-cell,
        :root[data-theme="dark"] .investment-capital,
        :root[data-theme="dark"] .investment-type,
        :root[data-theme="dark"] .investment-count,
        :root[data-theme="dark"] .investment-date {
            color: #ecf2ff !important;
        }

        .investment-raiser-name {
            font-weight: 600;
            color: var(--pt-text);
            font-size: 0.9375rem;
        }

        .investment-raiser-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .investment-raiser-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #5b8def, #3a7ee0);
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        :root[data-theme="dark"] .investment-raiser-avatar {
            background: linear-gradient(135deg, #5b8def, #3a7ee0);
        }

        .investment-capital,
        .investment-type,
        .investment-count,
        .investment-date {
            color: var(--pt-text);
            font-size: 0.9375rem;
        }

        .investment-capital {
            display: inline-flex;
            align-items: center;
            font-weight: 500;
            word-spacing: 0.15rem;
            white-space: nowrap;
        }

        .badge-stage {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: center;
        }

        .stage-fattening {
            background: rgba(239, 91, 108, 0.2);
            color: #ef5b6c;
        }

        .stage-farrowing {
            background: rgba(94, 168, 255, 0.2);
            color: #5ea8ff;
        }

        .stage-piglet {
            background: rgba(67, 203, 137, 0.2);
            color: #43cb89;
        }

        .upcoming-card {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
        }

        .timeline-list {
            flex: 1;
        }

        .timeline-item {
            display: flex;
            gap: 1rem;
            position: relative;
        }

        .timeline-marker {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 40px;
        }

        .timeline-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #5b8def;
            margin-top: 6px;
        }

        .timeline-content {
            flex: 1;
            min-width: 0;
        }

        .timeline-date {
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--pt-muted);
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .timeline-title {
            font-weight: 600;
            color: var(--pt-text);
            font-size: 0.9375rem;
            margin-bottom: 0.25rem;
        }

        .timeline-raiser {
            font-size: 0.875rem;
            color: var(--pt-muted);
        }

        .btn-link {
            color: #5b8def;
            text-decoration: none;
            font-weight: 600;
            padding: 0;
            border: none;
            background: none;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .btn-link:hover {
            color: #4a7fc8;
            text-decoration: underline;
        }

        .view-calendar {
            text-align: center;
            display: block;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--pt-border);
        }

        @media (max-width: 1200px) {
            .investment-table {
                font-size: 0.875rem;
            }

            .investment-table thead th,
            .investment-table tbody td {
                padding: 0.75rem;
            }
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--pt-text);
            margin-bottom: 1.5rem;
        }

        .btn-action-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 6px;
            background: transparent;
            color: var(--pt-text);
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 1.1rem;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .btn-action-icon:hover {
            background: rgba(91, 141, 239, 0.15);
            border-color: #5b8def;
            color: #5b8def;
        }

        .btn-action-icon i {
            line-height: 1;
        }
    </style>

    <!-- Calendar Modal -->
    <div id="fullCalendarModal" class="calendar-modal">
        <div class="calendar-modal-content">
            <button type="button" class="calendar-close-btn" id="calendarCloseBtn">
                <i class="bi bi-x"></i>
            </button>
            <div class="calendar-header">
                <button type="button" class="calendar-nav-btn" id="prevMonth">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <h3 class="calendar-title" id="calendarTitle">Month Year</h3>
                <button type="button" class="calendar-nav-btn" id="nextMonth">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
            <div class="calendar-weekdays">
                <div class="calendar-weekday">Sun</div>
                <div class="calendar-weekday">Mon</div>
                <div class="calendar-weekday">Tue</div>
                <div class="calendar-weekday">Wed</div>
                <div class="calendar-weekday">Thu</div>
                <div class="calendar-weekday">Fri</div>
                <div class="calendar-weekday">Sat</div>
            </div>
            <div class="calendar-dates" id="calendarDates"></div>
            <div id="calendarEventsList" class="calendar-events-list"></div>
        </div>
    </div>

    <style>
        /* Calendar Modal Styles */
        .calendar-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .calendar-modal.show {
            display: flex;
        }

        .calendar-modal-content {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 0.75rem;
            padding: 2rem;
            max-width: 700px;
            width: 95%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
        }

        :root[data-theme="dark"] .calendar-modal-content {
            background-color: #1b2638 !important;
            border-color: #28354a !important;
        }

        .calendar-close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: transparent;
            border: none;
            font-size: 1.75rem;
            cursor: pointer;
            color: var(--pt-muted);
            transition: color 0.2s ease;
            width: 2.5rem;
            height: 2.5rem;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            z-index: 10;
        }

        .calendar-close-btn:hover {
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .calendar-close-btn {
            color: #9cb0c9 !important;
        }

        :root[data-theme="dark"] .calendar-close-btn:hover {
            color: #ecf2ff !important;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            gap: 1.5rem;
        }

        .calendar-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--pt-text);
            margin: 0;
            flex: 1;
            text-align: center;
            letter-spacing: 0.02em;
        }

        :root[data-theme="dark"] .calendar-title {
            color: #ecf2ff !important;
        }

        .calendar-nav-btn {
            background: transparent;
            border: 1px solid var(--pt-border);
            color: var(--pt-text);
            width: 2.7rem;
            height: 2.7rem;
            border-radius: 0.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .calendar-nav-btn:hover {
            background-color: var(--pt-surface-soft);
            border-color: #5b8def;
            color: #5b8def;
        }

        :root[data-theme="dark"] .calendar-nav-btn {
            border-color: #28354a !important;
            color: #ecf2ff !important;
        }

        :root[data-theme="dark"] .calendar-nav-btn:hover {
            background-color: #243549 !important;
            border-color: #5b8def !important;
        }

        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .calendar-weekday {
            text-align: center;
            font-weight: 600;
            color: var(--pt-muted);
            font-size: 0.8rem;
            padding: 0.75rem 0;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .calendar-dates {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .calendar-date {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--pt-border);
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            background-color: var(--pt-surface);
            color: var(--pt-text);
            position: relative;
            min-height: 48px;
            padding: 0.5rem;
        }

        .calendar-date:hover:not(.disabled):not(.other-month) {
            border-color: #5b8def;
            background-color: rgba(91, 141, 239, 0.1);
        }

        .calendar-date.other-month {
            color: var(--pt-muted);
            background-color: transparent;
            border-color: transparent;
            cursor: default;
        }

        .calendar-date.today {
            font-weight: 700;
            border-color: #1a1a1a;
            background-color: #1a1a1a;
            color: white;
        }

        .calendar-date.has-event {
            background-color: #1a1a1a;
            color: white;
            border-color: #1a1a1a;
            font-weight: 700;
        }

        .calendar-date.has-event::after {
            content: '';
            position: absolute;
            bottom: 4px;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background-color: white;
        }

        .calendar-date.disabled {
            color: var(--pt-muted);
            cursor: not-allowed;
            background-color: transparent;
        }

        :root[data-theme="dark"] .calendar-date {
            border-color: #28354a !important;
            background-color: #151f2e !important;
        }

        :root[data-theme="dark"] .calendar-date:hover:not(.disabled):not(.other-month) {
            border-color: #5b8def !important;
            background-color: rgba(91, 141, 239, 0.15) !important;
        }

        :root[data-theme="dark"] .calendar-date.today {
            border-color: #ffffff !important;
            background-color: #ffffff !important;
            color: #1a1a1a !important;
            font-weight: 700;
        }

        :root[data-theme="dark"] .calendar-date.has-event {
            background-color: #ffffff !important;
            color: #1a1a1a !important;
            border-color: #ffffff !important;
            font-weight: 700;
        }

        :root[data-theme="dark"] .calendar-date.has-event::after {
            background-color: #1a1a1a !important;
        }

        .calendar-events-list {
            border-top: 2px solid var(--pt-border);
            padding-top: 2rem;
            margin-top: 2rem;
        }

        .calendar-events-list h4 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--pt-text);
            margin-bottom: 1.25rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        :root[data-theme="dark"] .calendar-events-list h4 {
            color: #ecf2ff !important;
        }

        .event-item {
            display: flex;
            gap: 1.25rem;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: var(--pt-surface-soft);
            border-left: 4px solid #5b8def;
            border-radius: 0.5rem;
        }

        :root[data-theme="dark"] .event-item {
            background-color: #243549 !important;
        }

        .event-marker {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #5b8def;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            margin-top: 2px;
        }

        .event-details {
            flex: 1;
        }

        .event-date {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--pt-muted);
            font-weight: 700;
            margin-bottom: 0.35rem;
        }

        .event-title {
            font-weight: 700;
            color: var(--pt-text);
            font-size: 0.95rem;
            margin-bottom: 0.35rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        :root[data-theme="dark"] .event-title {
            color: #ecf2ff !important;
        }

        .event-batch {
            font-size: 0.8rem;
            color: var(--pt-muted);
        }
    </style>

    <script>
        // Calendar Modal Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const calendarModal = document.getElementById('fullCalendarModal');
            const calendarCloseBtn = document.getElementById('calendarCloseBtn');
            const viewCalendarBtn = document.querySelector('.view-calendar');
            const prevMonthBtn = document.getElementById('prevMonth');
            const nextMonthBtn = document.getElementById('nextMonth');
            const calendarTitle = document.getElementById('calendarTitle');
            const calendarDates = document.getElementById('calendarDates');
            const eventsList = document.getElementById('calendarEventsList');
            
            let currentDate = new Date();
            let eventsData = {};

            // Extract events from the timeline
            function extractEvents() {
                const timelineItems = document.querySelectorAll('.timeline-item');
                eventsData = {};

                timelineItems.forEach(item => {
                    const dateEl = item.querySelector('.timeline-date');
                    const titleEl = item.querySelector('.timeline-title');
                    const raiserEl = item.querySelector('.timeline-raiser');

                    if (dateEl && titleEl) {
                        const dateKey = (dateEl.dataset.dateKey || '').trim();
                        const title = titleEl.textContent.trim();
                        const batch = raiserEl ? raiserEl.textContent.trim() : '';

                        if (dateKey) {
                            if (!eventsData[dateKey]) {
                                eventsData[dateKey] = [];
                            }
                            eventsData[dateKey].push({ title, batch });
                        }
                    }
                });
            }

            function renderCalendar() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                
                calendarTitle.textContent = currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const daysInMonth = lastDay.getDate();
                const startingDayOfWeek = firstDay.getDay();
                
                calendarDates.innerHTML = '';
                
                // Previous month's days
                const prevLastDay = new Date(year, month, 0).getDate();
                for (let i = startingDayOfWeek - 1; i >= 0; i--) {
                    const dateEl = document.createElement('div');
                    dateEl.className = 'calendar-date other-month';
                    dateEl.textContent = prevLastDay - i;
                    calendarDates.appendChild(dateEl);
                }
                
                // Current month's days
                const today = new Date();
                
                for (let day = 1; day <= daysInMonth; day++) {
                    const dateEl = document.createElement('div');
                    dateEl.className = 'calendar-date';
                    dateEl.textContent = day;
                    
                    const currentCellDate = new Date(year, month, day);
                    const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    
                    // Check if it's today
                    if (today.toDateString() === currentCellDate.toDateString()) {
                        dateEl.classList.add('today');
                    }
                    
                    // Check if it has events
                    if (eventsData[dateKey]) {
                        dateEl.classList.add('has-event');
                        dateEl.addEventListener('click', function() {
                            renderEventsList(dateKey);
                        });
                    }
                    
                    calendarDates.appendChild(dateEl);
                }
                
                // Next month's days
                const totalCells = calendarDates.children.length;
                const remainingCells = 42 - totalCells;
                for (let day = 1; day <= remainingCells; day++) {
                    const dateEl = document.createElement('div');
                    dateEl.className = 'calendar-date other-month';
                    dateEl.textContent = day;
                    calendarDates.appendChild(dateEl);
                }
            }

            function renderEventsList(dateKey) {
                if (!dateKey || !eventsData[dateKey]) {
                    eventsList.innerHTML = '<h4>Select a highlighted date to view events</h4>';
                    return;
                }

                const events = eventsData[dateKey] || [];
                const date = new Date(dateKey + 'T00:00:00');
                const dateStr = date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

                let html = `<h4>Events on ${dateStr}</h4>`;
                
                events.forEach((event, idx) => {
                    html += `
                        <div class="event-item">
                            <div class="event-marker">${idx + 1}</div>
                            <div class="event-details">
                                <div class="event-date">${dateKey}</div>
                                <div class="event-title">${event.title}</div>
                                <div class="event-batch">${event.batch}</div>
                            </div>
                        </div>
                    `;
                });

                eventsList.innerHTML = html;
            }

            if (viewCalendarBtn) {
                viewCalendarBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    extractEvents();
                    currentDate = new Date();
                    calendarModal.classList.add('show');
                    renderCalendar();
                    renderEventsList(null);
                });
            }

            if (calendarCloseBtn) {
                calendarCloseBtn.addEventListener('click', function() {
                    calendarModal.classList.remove('show');
                });
            }

            if (prevMonthBtn) {
                prevMonthBtn.addEventListener('click', function() {
                    currentDate.setMonth(currentDate.getMonth() - 1);
                    renderCalendar();
                    eventsList.innerHTML = '';
                });
            }

            if (nextMonthBtn) {
                nextMonthBtn.addEventListener('click', function() {
                    currentDate.setMonth(currentDate.getMonth() + 1);
                    renderCalendar();
                    eventsList.innerHTML = '';
                });
            }

            if (calendarModal) {
                calendarModal.addEventListener('click', function(e) {
                    if (e.target === calendarModal) {
                        calendarModal.classList.remove('show');
                    }
                });
            }
        });
    </script>
@endsection
