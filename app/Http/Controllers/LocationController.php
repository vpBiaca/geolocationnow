<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserLocation; // Assuming you want to store locations in the database

class LocationController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Store location in the database
        $location = new UserLocation();
        $location->latitude = $validated['latitude'];
        $location->longitude = $validated['longitude'];
        $location->save();

        // Return a JSON response
        return response()->json(['success' => 'Location stored successfully']);
    }
}
