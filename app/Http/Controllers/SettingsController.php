<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('pages.settings.index', [
            'pageTitle' => 'Settings',
            'profile' => [
                'name' => 'Admin',
                'email' => 'admin@piggytrunk.local',
                'role' => 'System Administrator',
                'phone' => '+63 917 555 0101',
            ],
            'systemPreferences' => [
                ['label' => 'Application Name', 'value' => 'PiggyTrunk Admin Portal'],
                ['label' => 'Default Theme', 'value' => 'Light / Dark Toggle'],
                ['label' => 'Currency Format', 'value' => '₱'],
                ['label' => 'Timezone', 'value' => 'PST (Philippine Standard Time)'],
            ],
            'referenceData' => [],
            'moduleRules' => [],
            'notifications' => [
                ['label' => 'Low stock alerts', 'status' => 'Enabled'],
                ['label' => 'Payout schedule reminders', 'status' => 'Enabled'],
                ['label' => 'New hog raiser updates', 'status' => 'Enabled'],
                ['label' => 'Retail order processing alerts', 'status' => 'Enabled'],
            ],
            'user' => $this->user(),
        ]);
    }

    private function user(): array
    {
        return [
            'name' => 'Admin',
            'role' => 'System Administrator',
            'initials' => 'DL',
        ];
    }
}
