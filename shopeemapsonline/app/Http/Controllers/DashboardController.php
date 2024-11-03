<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalUsers' => 1234,
            'activeLocations' => 56,
            'totalVisits' => 89421,
            'defaultLocation' => [
                'lat' => -6.2088,
                'lng' => 106.8456,
                'zoom' => 13
            ]
        ];

        return view('pengguna.dashboard', $data);
    }

    public function adminDashboard()
    {
        $data = [
            'totalUsers' => 1234,
            'activeLocations' => 56,
            'totalVisits' => 89421,
            'defaultLocation' => [
                'lat' => -6.2088,
                'lng' => 106.8456,
                'zoom' => 13
            ]
        ];

        return view('admin.dashboard', $data);
    }
}
