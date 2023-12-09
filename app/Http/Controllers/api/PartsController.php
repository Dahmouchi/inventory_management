<?php

namespace App\Http\Controllers\api;

use App\Events\SendPartsStockNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddPartsRequest;
use App\Http\Resources\PartsResourserc;
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
        return PartsResourserc::collection(Parts::all());
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

        $data['image'] = $imageName ;



        $part = Parts::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'sellPrice' => $data['sellPrice'],
            'purchasePrice' => $data['purchasePrice'],
            'quantity' => $data['quantity'],
            'admin_id' => $admin->id,
            'image' => $data['image'],
        ]);

        return response()->json(new PartsResourserc($part), 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $part = Parts::find($id);
        if ($part) {
            return response()->json(new PartsResourserc($part), 200);
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


                Storage::disk('public')->delete("images/".$part->image);
            }

            $data['image'] = $imageName;
            }


            $part->update($data);
                return response()->json(new PartsResourserc($part), 200);
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


            // Delete the image
            Storage::disk('public')->delete("images/".$part->image);


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


            // send notification
            if ($partModel->quantity <= 10) {
                event(
                    new SendPartsStockNotification($partModel,
                    "part: $partModel->name , id : $partModel->id is running out of stock"
            ));
            }else if($partModel->quantity == 0){
                event(
                    new SendPartsStockNotification($partModel,
                    "part: $partModel->name , id : $partModel->id is out of stock"
            ));
            }




            return response()->json(['message' => "Parts bought successfully"], 200);
        }

    }


}
