<?php

namespace App\Core\Features\Http\Controllers;

use App\Core\Features\Models\Feature;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index()
    {
        return Feature::all();
    }

    public function store(Request $request)
    {
        return Feature::query()->create($request->validate([
            'key' => 'required|unique:features',
            'name' => 'required',
        ]));
    }

    public function update(Request $request, Feature $feature)
    {
        $feature->update($request->all());

        return response()->json($feature->fresh());
    }

    public function destroy(Feature $feature)
    {
        $feature->delete();

        return response()->json([
            'message' => 'Feature deleted successfully!',
        ], 204);
    }
}
