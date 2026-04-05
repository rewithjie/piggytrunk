@php
    use App\Support\AdminAsset;

    $adminCssVersion = AdminAsset::version(resource_path('css/app.css'));
    $adminJsVersion = AdminAsset::version(public_path('js/admin.js'));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $pageTitle ?? 'PiggyTrunk Admin Portal' }}</title>
        <script>
            (function () {
                var sidebarStorageKey = 'piggytrunk-sidebar-expanded';
                var themeStorageKey = 'piggytrunk-theme';
                var savedState = window.localStorage.getItem(sidebarStorageKey);
                var savedTheme = window.localStorage.getItem(themeStorageKey);
                var isDesktop = window.matchMedia('(min-width: 992px)').matches;
                var expanded = savedState === null ? isDesktop : savedState === 'true';
                document.documentElement.classList.add(expanded ? 'sidebar-expanded' : 'sidebar-collapsed');
                document.documentElement.setAttribute('data-theme', savedTheme === 'dark' ? 'dark' : 'light');
            })();
        </script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <link href="{{ route('assets.admin.css', ['v' => $adminCssVersion]) }}" rel="stylesheet">
    </head>
    <body class="admin-body">
        <div class="admin-shell">
            @include('partials.sidebar', ['user' => $user ?? null])

            <div class="sidebar-overlay" data-sidebar-close></div>

            <div class="admin-content">
                @include('partials.topbar', ['user' => $user ?? null])

                <div class="content-frame">
                    <main class="content-inner px-3 px-lg-4 py-4 py-lg-5">
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="{{ route('assets.admin.js', ['v' => $adminJsVersion]) }}"></script>
    </body>
</html>
