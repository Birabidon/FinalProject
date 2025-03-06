<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class MarkerController extends Controller
{
    public function getAllMarkers()
    {
        $markers = Location::all();

        return inertia('home', ['markers' => $markers]);
    }
}
