<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarkerController extends Controller
{
    public function index()
    {
        $markers = [
            [
                'id' => 0,
                'title' => 'Marker 1',
                'lat' => 56.9496,
                'lng' => 24.1052,
            ],
            [
                'id' => 1,
                'title' => 'Marker 2',
                'lat' => 56.9400,
                'lng' => 24.1052,
            ],
            [
                'id' => 2,
                'title' => 'Marker 3',
                'lat' => 56.9300,
                'lng' => 24.1052,
            ],
        ];
        return inertia('home', ['markers' => $markers]);
    }
}
