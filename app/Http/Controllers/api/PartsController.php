<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddPartsRequest;
use App\Models\Parts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // get auth admin
        $admin = Auth::user();

        $data = $request->validated();

        $part = Parts::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'sellPrice' => $data['sellPrice'],
            'purchasePrice' => $data['purchasePrice'],
            'quantity' => $data['quantity'],
            'admin_id' => $admin->id,
        ]);

        return response()->json($part, 201);

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


    public function buy(Request $request){

        $parts = $request->parts;

        $request->validate([
            'parts' => 'required|array',
            'parts.*.quantity' => 'required|integer|min:1',
            'parts.*.id' => 'required|integer|exists:parts,id',
        ]);

        // decrease quantity of parts
        foreach ($parts as $part) {
            $partModel = Parts::find($part['id']);

            // check if quantity is enough
            if ($partModel->quantity < $part['quantity']) {
                return response()->json(['message' => "Not enough quantity for part: $partModel->name , id : $partModel->id" ], 400);
            }

            $partModel->update([
                'quantity' => $partModel->quantity - $part['quantity']
            ]);

            return response()->json(['message' => "Parts bought successfully"], 200);
        }

    }


}
