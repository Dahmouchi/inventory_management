<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddPartsRequest;
use App\Models\Parts;
use Illuminate\Http\Request;

class PartsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Parts::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddPartsRequest $request)
    {
        $data = $request->validated();
        $parts = Parts::create($data);
        return response()->json($parts, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $part = Parts::find($id);
        if ($part) {
            return response()->json($part, 200);
        } else {
            return response()->json(['message' => 'Part not found'], 404);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $part = Parts::find($id);
        if ($part) {
            $part->update($request->all());
            return response()->json($part, 200);
        } else {
            return response()->json(['message' => 'Part not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $part = Parts::find($id);
        if ($part) {
            $part->delete();
            return response()->json(['message' => 'Part deleted'], 200);
        } else {
            return response()->json(['message' => 'Part not found'], 404);
        }
    }


}
