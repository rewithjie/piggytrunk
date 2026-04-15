@php
    $topbarNotifications = [];
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
                        <div class="topbar-notification-list">
                            @forelse ($topbarNotifications as $notification)
                                <div class="topbar-notification-item">
                                    <div class="topbar-notification-item-title">{{ $notification['title'] }}</div>
                                    <div class="topbar-notification-item-copy">{{ $notification['message'] }}</div>
                                    <div class="topbar-notification-item-time">{{ $notification['time'] }}</div>
                                </div>
                            @empty
                                <div class="topbar-notification-item text-center py-4">
                                    <div class="topbar-notification-item-title">No notifications as of now</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                @if ($user)
                    <a
                        class="topbar-profile-trigger text-decoration-none"
                        href="{{ route('settings.index') }}"
                        aria-label="Open profile and settings"
                    >
                        <span class="topbar-profile-avatar" data-topbar-profile-avatar style="background-color: transparent !important;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="profile-icon" data-theme-icon>
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
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

<script>
    (() => {
        const profilePictureStorageKey = 'piggytrunk-settings-profile-picture';
        const profileStorageKey = 'piggytrunk-settings-profile';
        const topbarProfileAvatar = document.querySelector('[data-topbar-profile-avatar]');
        const topbarProfileName = document.querySelector('.topbar-profile-name');
        const topbarProfileRole = document.querySelector('.topbar-profile-role');
        const profileIcon = topbarProfileAvatar?.querySelector('.profile-icon');

        const getThemeColor = () => {
            const theme = document.documentElement.getAttribute('data-theme') || 'light';
            return theme === 'dark' ? '#FFFFFF' : '#1F2937';
        };

        const applyThemeColor = () => {
            if (!topbarProfileAvatar || !profileIcon) return;
            const storedProfilePicture = window.localStorage.getItem(profilePictureStorageKey);
            if (!storedProfilePicture) {
                topbarProfileAvatar.style.color = getThemeColor();
                topbarProfileAvatar.style.backgroundColor = 'transparent';
            }
        };

        const updateProfilePicture = () => {
            if (!topbarProfileAvatar) return;
            
            const storedProfilePicture = window.localStorage.getItem(profilePictureStorageKey);
            
            if (storedProfilePicture) {
                topbarProfileAvatar.style.backgroundImage = `url('${storedProfilePicture}')`;
                topbarProfileAvatar.style.backgroundSize = 'cover';
                topbarProfileAvatar.style.backgroundPosition = 'center';
                if (profileIcon) profileIcon.style.display = 'none';
            } else {
                topbarProfileAvatar.style.backgroundImage = '';
                applyThemeColor();
                if (profileIcon) profileIcon.style.display = '';
            }
        };

        const updateProfile = () => {
            const storedProfile = window.localStorage.getItem(profileStorageKey);
            if (storedProfile) {
                try {
                    const profile = JSON.parse(storedProfile);
                    if (profile.name && topbarProfileName) {
                        topbarProfileName.textContent = profile.name;
                    }
                    if (profile.role && topbarProfileRole) {
                        topbarProfileRole.textContent = profile.role;
                    }
                } catch (e) {
                    console.error('Error parsing profile data:', e);
                }
            } else {
                // Use default values from the page when localStorage is empty (reset)
                if (topbarProfileName) {
                    topbarProfileName.textContent = '{{ $user["name"] }}';
                }
                if (topbarProfileRole) {
                    topbarProfileRole.textContent = '{{ $user["role"] }}';
                }
            }
        };

        // Initial load
        updateProfilePicture();
        updateProfile();
        
        // Listen for custom event from settings page
        window.addEventListener('profileupdated', (event) => {
            updateProfilePicture();
            updateProfile();
        });

        // Listen for theme changes
        window.addEventListener('themechange', (event) => {
            applyThemeColor();
        });
        
        // Also listen for storage changes (from other tabs)
        window.addEventListener('storage', (event) => {
            if (event.key === profilePictureStorageKey) {
                updateProfilePicture();
            }
            if (event.key === profileStorageKey) {
                updateProfile();
            }
        });
    })();
</script>
