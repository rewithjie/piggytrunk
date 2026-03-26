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
                'name' => 'De Luna Admin',
                'email' => 'admin@piggytrunk.local',
                'role' => 'System Administrator',
                'phone' => '+63 917 555 0101',
            ],
            'systemPreferences' => [
                ['label' => 'Application Name', 'value' => 'PiggyTrunk Admin Portal'],
                ['label' => 'Default Theme', 'value' => 'Light / Dark Toggle'],
                ['label' => 'Currency Format', 'value' => 'PHP'],
                ['label' => 'Timezone', 'value' => 'PST (Philippine Standard Time)'],
            ],
            'referenceData' => [
                [
                    'title' => 'Hog Raiser Defaults',
                    'items' => ['Active, Inactive statuses', 'Male Pig, Female Pig types', 'RSR batch/code format'],
                ],
                [
                    'title' => 'Retail Reference Data',
                    'items' => ['Feeds, Vitamins, Medicines', 'Walk-in, Facebook Shop, Messenger', 'Completed, Packed, For Delivery'],
                ],
                [
                    'title' => 'Investment Reference Data',
                    'items' => ['Gold, Silver, Growth partner tiers', 'Upcoming, Scheduled, For Review payout states', 'Piglet, Farrowing, Fattening stages'],
                ],
            ],
            'moduleRules' => [
                [
                    'title' => 'Retail Shop Rules',
                    'description' => 'Low-stock threshold, sales channel labels, and order status flow.',
                    'highlights' => ['Low stock alert at 10 units', 'Weekly sales tracking enabled', 'Three active sales channels'],
                ],
                [
                    'title' => 'Investment Rules',
                    'description' => 'ROI presets, payout cycle labels, and batch capital monitoring.',
                    'highlights' => ['Projected ROI range: 16.8% to 19.2%', 'Next payout release tracked per batch', 'Capital allocation monitored by cycle progress'],
                ],
            ],
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
            'name' => 'De Luna Admin',
            'role' => 'System Administrator',
            'initials' => 'DL',
        ];
    }
}
