@php
    $navItems = [
        ['label' => 'Dashboard', 'icon' => 'bi-grid', 'route' => 'dashboard'],
        ['label' => 'Hog Raiser Directory', 'icon' => 'bi-person', 'route' => 'raisers.index'],
        ['label' => 'Investment', 'icon' => 'bi-journal-text', 'route' => 'investment.index'],
        ['label' => 'Retail Shop', 'icon' => 'bi-clipboard-check', 'route' => 'retail.index'],
        ['label' => 'Inventory', 'icon' => 'bi-bag', 'route' => 'inventory.index'],
        ['label' => 'Settings', 'icon' => 'bi-gear', 'route' => 'settings.index'],
    ];
@endphp

<aside class="admin-sidebar border-end" id="adminSidebar">
    <div class="admin-sidebar-inner d-flex flex-column h-100">
        <div class="sidebar-brand-row px-3 px-lg-4 py-3 py-lg-4">
            <a href="{{ route('dashboard') }}" class="brand-mark text-decoration-none">
                <span class="brand-logo-shell">
                    <img src="{{ asset('piggytrunkremovebg.png') }}" alt="PiggyTrunk logo" class="brand-logo">
                </span>
                <span class="brand-text">PiggyTrunk</span>
            </a>
        </div>

        <div class="px-3 px-lg-3 pb-3">
            <button class="sidebar-toggle-button" type="button" data-sidebar-toggle aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>
        </div>

        <nav class="px-3 px-lg-3 pb-3 flex-grow-1">
            <div class="nav flex-column gap-2">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}" class="sidebar-link {{ request()->routeIs($item['route']) ? 'active' : '' }}" title="{{ $item['label'] }}">
                        <i class="bi {{ $item['icon'] }}"></i>
                        <span class="sidebar-label">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>

        <div class="sidebar-footer px-3 px-lg-3 py-3">
            <button class="sidebar-theme-toggle" type="button" data-theme-toggle aria-label="Toggle dark mode" title="Toggle dark mode">
                <i class="bi bi-moon-stars-fill" data-theme-icon></i>
                <span class="sidebar-label">Theme</span>
            </button>

            @if ($user)
                <a href="{{ route('settings.index') }}" class="sidebar-user text-decoration-none" title="{{ $user['name'] }}">
                    <span class="sidebar-user-avatar">{{ $user['initials'] }}</span>
                    <span class="sidebar-user-copy">
                        <span class="sidebar-user-name">{{ $user['name'] }}</span>
                        <span class="sidebar-user-role">{{ $user['role'] }}</span>
                    </span>
                </a>
            @endif
        </div>
    </div>
</aside>
