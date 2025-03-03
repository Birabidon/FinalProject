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
    public function create(){
        return inertia('Location/Create');
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
