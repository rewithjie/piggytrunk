@extends('layouts.admin')

@section('content')
    <section class="settings-page">
        <div class="row g-4 mb-4">
            <div class="col-12 col-xl-4">
                <div class="card dashboard-bootstrap-card h-100">
                    <div class="card-body">
                        <p class="section-label mb-2">Admin Profile</p>
                        <h3 class="section-heading mb-4">Account Center</h3>

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <span class="sidebar-user-avatar settings-admin-avatar" style="width: 60px; height: 60px;" data-settings-avatar>
                                {{ $user['initials'] }}
                            </span>
                            <div>
                                <div class="table-name" data-settings-profile-name>{{ $profile['name'] }}</div>
                                <div class="table-meta" data-settings-profile-role>{{ $profile['role'] }}</div>
                            </div>
                        </div>

                        <form class="settings-stack" data-settings-profile-form>
                            <div>
                                <label class="form-label">Admin Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $profile['name'] }}">
                            </div>
                            <div>
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $profile['email'] }}">
                            </div>
                            <div>
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control" name="role" value="{{ $profile['role'] }}">
                            </div>
                            <div>
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" value="{{ $profile['phone'] }}">
                            </div>
                            <div class="settings-row">
                                <span class="table-meta">Access</span>
                                <span class="investment-pill">Full Admin</span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-dark btn-action">Save Profile</button>
                                <button type="button" class="btn btn-outline-secondary btn-action" data-settings-profile-reset>Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-8">
                <div class="card dashboard-bootstrap-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="section-label mb-1">System Preferences</p>
                                <h3 class="chart-title mb-0">Portal Defaults</h3>
                            </div>
                        </div>

                        <form class="row g-3" data-settings-preferences-form>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Application Name</label>
                                <input type="text" class="form-control" name="application_name" value="{{ $systemPreferences[0]['value'] }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Default Theme</label>
                                <select class="form-select" name="default_theme">
                                    <option value="light-dark-toggle" selected>Light / Dark Toggle</option>
                                    <option value="light">Light</option>
                                    <option value="dark">Dark</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Currency Format</label>
                                <input type="text" class="form-control" name="currency_format" value="PHP" readonly>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Timezone</label>
                                <select class="form-select" name="timezone">
                                    <option value="PST (Philippine Standard Time)" selected>PST (Philippine Standard Time)</option>
                                    <option value="UTC">UTC</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-dark btn-action">Save Preferences</button>
                                    <button type="button" class="btn btn-outline-secondary btn-action" data-settings-preferences-reset>Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-xl-7">
                <div class="card dashboard-bootstrap-card h-100">
                    <div class="card-body">
                        <p class="section-label mb-1">Reference Data</p>
                        <h3 class="chart-title mb-4">Admin-Controlled Defaults</h3>

                        <div class="settings-stack">
                            @foreach ($referenceData as $group)
                                <div class="settings-panel">
                                    <div class="table-name mb-2">{{ $group['title'] }}</div>
                                    @foreach ($group['items'] as $item)
                                        <div class="settings-bullet">{{ $item }}</div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="card dashboard-bootstrap-card h-100">
                    <div class="card-body">
                        <p class="section-label mb-1">Notifications</p>
                        <h3 class="chart-title mb-4">Active Alerts</h3>

                        <div class="settings-stack">
                            @foreach ($notifications as $item)
                                <div class="settings-row">
                                    <span class="settings-value">{{ $item['label'] }}</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge rounded-pill status-badge status-badge-active" data-setting-status>{{ $item['status'] }}</span>
                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary btn-sm settings-toggle-button"
                                            data-setting-toggle
                                            data-setting-key="{{ \Illuminate\Support\Str::slug($item['label'], '-') }}"
                                        >
                                            Toggle
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card dashboard-bootstrap-card">
            <div class="card-body">
                <p class="section-label mb-1">Module Rules</p>
                <h3 class="chart-title mb-4">Retail and Investment Controls</h3>

                <div class="row g-4">
                    @foreach ($moduleRules as $rule)
                        <div class="col-12 col-lg-6">
                            <div class="settings-panel h-100">
                                <div class="table-name mb-2">{{ $rule['title'] }}</div>
                                <div class="table-meta mb-3">{{ $rule['description'] }}</div>
                                @foreach ($rule['highlights'] as $highlight)
                                    <div class="settings-bullet">{{ $highlight }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <script>
        (() => {
            const storagePrefix = 'piggytrunk-setting-notification-';
            const profileStorageKey = 'piggytrunk-settings-profile';
            const preferencesStorageKey = 'piggytrunk-settings-preferences';

            const profileForm = document.querySelector('[data-settings-profile-form]');
            const profileName = document.querySelector('[data-settings-profile-name]');
            const profileRole = document.querySelector('[data-settings-profile-role]');
            const profileAvatar = document.querySelector('[data-settings-avatar]');
            const profileReset = document.querySelector('[data-settings-profile-reset]');

            const preferencesForm = document.querySelector('[data-settings-preferences-form]');
            const preferencesReset = document.querySelector('[data-settings-preferences-reset]');

            const defaultProfile = profileForm ? Object.fromEntries(new FormData(profileForm).entries()) : null;
            const defaultPreferences = preferencesForm ? Object.fromEntries(new FormData(preferencesForm).entries()) : null;

            const initialsFromName = (name) => {
                return (name || '')
                    .trim()
                    .split(/\s+/)
                    .slice(0, 2)
                    .map((part) => part.charAt(0).toUpperCase())
                    .join('') || 'DL';
            };

            const applyProfile = (values) => {
                if (!profileForm || !values) {
                    return;
                }

                Object.entries(values).forEach(([key, value]) => {
                    const field = profileForm.elements.namedItem(key);
                    if (field) {
                        field.value = value;
                    }
                });

                if (profileName) {
                    profileName.textContent = values.name || '';
                }

                if (profileRole) {
                    profileRole.textContent = values.role || '';
                }

                if (profileAvatar) {
                    profileAvatar.textContent = initialsFromName(values.name);
                }
            };

            const applyPreferences = (values) => {
                if (!preferencesForm || !values) {
                    return;
                }

                Object.entries(values).forEach(([key, value]) => {
                    const field = preferencesForm.elements.namedItem(key);
                    if (field) {
                        field.value = value;
                    }
                });

                if (values.default_theme === 'light' || values.default_theme === 'dark') {
                    document.documentElement.setAttribute('data-theme', values.default_theme);
                    window.localStorage.setItem('piggytrunk-theme', values.default_theme);
                    window.dispatchEvent(new CustomEvent('themechange', { detail: { theme: values.default_theme } }));
                }
            };

            if (profileForm && defaultProfile) {
                const storedProfile = window.localStorage.getItem(profileStorageKey);
                applyProfile(storedProfile ? JSON.parse(storedProfile) : defaultProfile);

                profileForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const values = Object.fromEntries(new FormData(profileForm).entries());
                    window.localStorage.setItem(profileStorageKey, JSON.stringify(values));
                    applyProfile(values);
                });

                profileReset?.addEventListener('click', () => {
                    window.localStorage.removeItem(profileStorageKey);
                    applyProfile(defaultProfile);
                });
            }

            if (preferencesForm && defaultPreferences) {
                const storedPreferences = window.localStorage.getItem(preferencesStorageKey);
                applyPreferences(storedPreferences ? JSON.parse(storedPreferences) : defaultPreferences);

                preferencesForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const values = Object.fromEntries(new FormData(preferencesForm).entries());
                    window.localStorage.setItem(preferencesStorageKey, JSON.stringify(values));
                    applyPreferences(values);
                });

                preferencesReset?.addEventListener('click', () => {
                    window.localStorage.removeItem(preferencesStorageKey);
                    applyPreferences(defaultPreferences);
                });
            }

            document.querySelectorAll('[data-setting-toggle]').forEach((button) => {
                const row = button.closest('.settings-row');
                const badge = row?.querySelector('[data-setting-status]');
                const key = button.dataset.settingKey;

                if (!badge || !key) {
                    return;
                }

                const applyState = (enabled) => {
                    badge.textContent = enabled ? 'Enabled' : 'Disabled';
                    badge.classList.toggle('status-badge-active', enabled);
                    badge.classList.toggle('status-badge-inactive', !enabled);
                    button.textContent = enabled ? 'Disable' : 'Enable';
                };

                const savedValue = window.localStorage.getItem(storagePrefix + key);
                const initialEnabled = savedValue === null ? true : savedValue === 'true';
                applyState(initialEnabled);

                button.addEventListener('click', () => {
                    const nextEnabled = badge.textContent !== 'Enabled';
                    window.localStorage.setItem(storagePrefix + key, nextEnabled ? 'true' : 'false');
                    applyState(nextEnabled);
                });
            });
        })();
    </script>
@endsection
