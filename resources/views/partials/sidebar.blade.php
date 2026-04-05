@php
    $navItems = [
        ['label' => 'Dashboard', 'icon' => 'bi-grid', 'route' => 'dashboard'],
        ['label' => 'Hog Raiser', 'icon' => 'bi-person', 'route' => 'raisers.index'],
        ['label' => 'Investment', 'icon' => 'bi-journal-text', 'route' => 'investments.index'],
        ['label' => 'Retail Shop', 'icon' => 'bi-clipboard-check', 'route' => 'retail.index'],
        ['label' => 'Inventory', 'icon' => 'bi-bag', 'route' => 'inventory.index'],
    ];
@endphp

<aside class="admin-sidebar border-end" id="adminSidebar">
    <div class="admin-sidebar-inner d-flex flex-column h-100">
        <div class="sidebar-brand-row px-3 px-lg-4 py-3 py-lg-4">
            <button class="sidebar-toggle-button sidebar-brand-toggle" type="button" data-sidebar-toggle aria-label="Toggle navigation">
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
            <button class="sidebar-link sidebar-theme-toggle" type="button" data-theme-toggle aria-label="Toggle dark mode" title="Toggle dark mode">
                <i class="bi bi-moon-stars-fill" data-theme-icon></i>
                <span class="sidebar-label">Theme</span>
            </button>

            <a href="{{ route('settings.index') }}" class="sidebar-link text-decoration-none {{ request()->routeIs('settings.index') ? 'active' : '' }}" title="Settings">
                <i class="bi bi-gear"></i>
                <span class="sidebar-label">Settings</span>
            </a>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="sidebar-link sidebar-logout-button" type="submit" title="Sign out">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="sidebar-label">Sign out</span>
                </button>
            </form>
        </div>
    </div>
</aside>
