@php
    $topbarNotifications = [
        [
            'title' => 'Low stock alert',
            'message' => 'Piglet Starter Mix is down to 12 packs.',
            'time' => '5 mins ago',
        ],
        [
            'title' => 'Retail order received',
            'message' => 'A new Facebook Shop order is waiting for packing.',
            'time' => '18 mins ago',
        ],
        [
            'title' => 'Payout reminder',
            'message' => 'Batch-01 investor payout is coming up this week.',
            'time' => '1 hour ago',
        ],
    ];
@endphp

<header class="topbar border-bottom">
    <div class="container-fluid px-3 px-lg-4 py-3">
        <div class="topbar-main">
            <button class="topbar-burger-button" type="button" data-sidebar-toggle aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>
            <div class="topbar-brand">
                <a href="{{ route('dashboard') }}" class="brand-mark text-decoration-none">
                    <span class="brand-logo-shell">
                        <img src="{{ asset('piggytrunkremovebg.png') }}" alt="PiggyTrunk logo" class="brand-logo">
                    </span>
                    <span class="brand-text">PiggyTrunk</span>
                </a>
            </div>
            <div class="topbar-copy">
                <div class="topbar-heading-copy">
                    @unless (!empty($hideTopbarTitle))
                        <h1 class="topbar-title mb-0">{{ $pageTitle ?? 'Dashboard' }}</h1>
                    @endunless
                </div>
            </div>

            <div class="topbar-actions">
                <div class="topbar-clock" data-live-clock data-timezone="Asia/Manila">
                    <div class="topbar-clock-value">--:--:--</div>
                </div>

                <div class="dropdown">
                    <button
                        class="topbar-bell"
                        type="button"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                        aria-label="Open notifications"
                    >
                        <i class="bi bi-bell"></i>
                        <span class="topbar-bell-badge">{{ count($topbarNotifications) }}</span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end topbar-notification-menu border-0 p-0">
                        <div class="topbar-notification-head">
                            <div class="topbar-notification-title">Notifications</div>
                            <div class="topbar-notification-subtitle">Dummy data for now, ready for real-time later</div>
                        </div>

                        <div class="topbar-notification-list">
                            @foreach ($topbarNotifications as $notification)
                                <div class="topbar-notification-item">
                                    <div class="topbar-notification-item-title">{{ $notification['title'] }}</div>
                                    <div class="topbar-notification-item-copy">{{ $notification['message'] }}</div>
                                    <div class="topbar-notification-item-time">{{ $notification['time'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if ($user)
                    <a
                        class="topbar-profile-trigger text-decoration-none"
                        href="{{ route('settings.index') }}"
                        aria-label="Open profile and settings"
                    >
                        <span class="topbar-profile-avatar">{{ $user['initials'] }}</span>
                        <span class="topbar-profile-copy d-none d-sm-flex">
                            <span class="topbar-profile-name">{{ $user['name'] }}</span>
                            <span class="topbar-profile-role">{{ $user['role'] }}</span>
                        </span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>
