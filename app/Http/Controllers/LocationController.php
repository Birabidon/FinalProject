<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function index()
    {
        return inertia('Location/Index');
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
