import './bootstrap';

const root = document.documentElement;
const storageKey = 'piggytrunk-sidebar-expanded';
const themeStorageKey = 'piggytrunk-theme';
const toggleButtons = document.querySelectorAll('[data-sidebar-toggle]');
const closeButtons = document.querySelectorAll('[data-sidebar-close]');
const themeButtons = document.querySelectorAll('[data-theme-toggle]');
const themeIcons = document.querySelectorAll('[data-theme-icon]');
const liveClocks = document.querySelectorAll('[data-live-clock]');
const desktopQuery = window.matchMedia('(min-width: 992px)');

const persistSidebarState = (expanded) => {
    window.localStorage.setItem(storageKey, expanded ? 'true' : 'false');
};

const setSidebarState = (expanded) => {
    root.classList.toggle('sidebar-expanded', expanded);
    root.classList.toggle('sidebar-collapsed', !expanded);
    persistSidebarState(expanded);
};

const setTheme = (theme) => {
    root.setAttribute('data-theme', theme);
    window.localStorage.setItem(themeStorageKey, theme);
    window.dispatchEvent(new CustomEvent('themechange', { detail: { theme } }));

    themeIcons.forEach((icon) => {
        icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
    });
};

const syncSidebarForViewport = () => {
    const savedState = window.localStorage.getItem(storageKey);

    if (savedState === null) {
        setSidebarState(desktopQuery.matches);
        return;
    }

    setSidebarState(savedState === 'true');
};

toggleButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const isExpanded = root.classList.contains('sidebar-expanded');
        setSidebarState(!isExpanded);
    });
});

closeButtons.forEach((button) => {
    button.addEventListener('click', () => {
        setSidebarState(false);
    });
});

desktopQuery.addEventListener('change', () => {
    syncSidebarForViewport();
});

themeButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        setTheme(nextTheme);
    });
});

liveClocks.forEach((clock) => {
    const value = clock.querySelector('.topbar-clock-value');
    const timezone = clock.dataset.timezone || 'Asia/Manila';

    if (!value) {
        return;
    }

    const formatter = new Intl.DateTimeFormat('en-PH', {
        timeZone: timezone,
        hour: 'numeric',
        minute: '2-digit',
        second: '2-digit',
        hour12: true,
    });

    const updateClock = () => {
        value.textContent = formatter.format(new Date());
    };

    updateClock();
    window.setInterval(updateClock, 1000);
});

setTheme(window.localStorage.getItem(themeStorageKey) === 'dark' ? 'dark' : 'light');
syncSidebarForViewport();
