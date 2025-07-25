<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Get user preferences (you can expand this based on your needs)
        $settings = [
            'notifications' => [
                'email_notifications' => true,
                'low_stock_alerts' => true,
                'order_updates' => true,
                'system_maintenance' => false,
            ],
            'display' => [
                'items_per_page' => 10,
                'date_format' => 'M d, Y',
                'time_format' => '12h',
                'timezone' => 'UTC',
            ],
            'security' => [
                'two_factor_enabled' => false,
                'session_timeout' => 30,
                'login_alerts' => true,
            ]
        ];

        return view('settings.index', compact('user', 'settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'low_stock_alerts' => 'boolean',
            'order_updates' => 'boolean',
            'system_maintenance' => 'boolean',
            'items_per_page' => 'integer|min:5|max:100',
            'date_format' => 'string|max:20',
            'time_format' => 'in:12h,24h',
            'timezone' => 'string|max:50',
            'two_factor_enabled' => 'boolean',
            'session_timeout' => 'integer|min:5|max:120',
            'login_alerts' => 'boolean',
        ]);

        // Here you would typically save settings to database
        // For now, we'll just show a success message
        
        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }
} 