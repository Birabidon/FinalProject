<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function index()
    {
        $markers = Location::all();
        return inertia('Location/Index', ['markers' => $markers]);
    }

    /**
     *  Show the form for creating a new resource.
     */
    public function create(Request $request){
        return inertia('Location/Create', [
            'lat' => (float)$request->lat,
            'lng' => (float)$request->lng
        ]);
    }

    public function store(Request $request){
        $fields = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
        ]);

        Location::create($fields);

        return redirect()->route('home')->withMessage('Location created successfully');
    }

    public function show(Location $location){

    }

    public function edit(Location $location){

    }

    public function update(Request $request, Location $location){

    }

    public function destroy(Location $location){

    }
}
