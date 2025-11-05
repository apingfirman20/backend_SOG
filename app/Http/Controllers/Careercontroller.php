<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;

class Careercontroller extends Controller
{
    public function index()
    {
        $career = Career::all();
        return response()->json($career);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'salary' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'link' => 'required|string|max:255',
            
        ]);

        $career = Career::create($validated);
        $career->update_date = now();
        $career->save();
        
        return response()->json([
            'message' => 'Career entry created successfully!',
            'data' => $career
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $career = Career::find($id);
        if (!$career) {
            return response()->json(['message' => 'Career entry not found'], 404);
        }

        $validated = $request->validate([
            'position' => 'sometimes|required|string|max:255',
            'company' => 'sometimes|required|string|max:255',
            'salary' => 'sometimes|required|string|max:100',
            'location' => 'sometimes|required|string|max:255',
            'link' => 'sometimes|required|string|max:255',
            
        ]);


        $career->update($validated);
        $career->update_date = now();
        $career->save();
        return response()->json([
            'message' => 'Career entry updated successfully!',
            'data' => $career
        ]);


    }

    public function destroy($id)
    {
        $career = Career::find($id);
        if (!$career) {
            return response()->json(['message' => 'Career entry not found'], 404);
        }

        $career->delete();

        return response()->json(['message' => 'Career entry deleted successfully!']);
    }
}
