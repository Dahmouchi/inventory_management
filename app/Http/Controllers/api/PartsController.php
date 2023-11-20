<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddPartsRequest;
use App\Models\Parts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        // upload image
        $image = $request->file('image');
        $imageName =  $image->getClientOriginalName().'_'. time() . '.' . $image->extension();

        $path = $image->storeAs('public/images', $imageName);


        if (!$path) {
            return response()->json(['message' => 'Error in uploading image'], 500);
        }

        $data['image'] = Storage::disk("public")->url("/images/{$imageName}");



        $part = Parts::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'sellPrice' => $data['sellPrice'],
            'purchasePrice' => $data['purchasePrice'],
            'quantity' => $data['quantity'],
            'admin_id' => $admin->id,
            'image' => $data['image'],
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

            $data = $request->validate([
                'name' => 'string',
                'description' => 'string',
                'sellPrice' => 'numeric|min:0',
                'purchasePrice' => 'numeric|min:0',
                'quantity' => 'integer|min:0',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',

            ]);

            if($request->hasFile('image')){
                // upload image
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName().'_'. time() . '.' . $image->extension();

            $path = $image->storeAs('public/images', $imageName);



            if (!$path) {
                return response()->json(['message' => 'Error in uploading image'], 500);
            }

            if($path && $part->image){

                $imagePath = "images/".explode('/', $part->image)[6];

                Storage::disk('public')->delete($imagePath);
            }

            $data['image'] = Storage::disk("public")->url("/images/{$imageName}");
            }


            $part->update($data);
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

            // Get image name
            $imagePath = "images/".explode('/', $part->image)[5];

            // Delete the image
            Storage::disk('public')->delete($imagePath);


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
