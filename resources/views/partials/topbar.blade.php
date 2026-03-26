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
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <p class="eyebrow mb-1">PiggyTrunk Admin Portal</p>
                @unless (!empty($hideTopbarTitle))
                    <h1 class="topbar-title mb-0">{{ $pageTitle ?? 'Dashboard' }}</h1>
                @endunless
            </div>

            <div class="d-flex align-items-center gap-2">
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
            </div>
        </div>
    </div>
</header>
