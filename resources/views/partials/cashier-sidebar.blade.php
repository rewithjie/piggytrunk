@php
    $navItems = [
        ['label' => 'Retail', 'icon' => 'bi-clipboard-check', 'route' => 'cashier.retail'],
        ['label' => 'Inventory', 'icon' => 'bi-bag', 'route' => 'cashier.inventory'],
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

            <form method="POST" action="{{ route('cashier.logout') }}">
                @csrf
                <button class="sidebar-link sidebar-logout-button" type="submit" title="Sign out">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="sidebar-label">Sign out</span>
                </button>
            </form>
        </div>
    </div>
</aside>
