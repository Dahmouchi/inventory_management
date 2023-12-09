<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PartsResourserc extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sellPrice' => $this->sellPrice,
            'purchasePrice' => $this->purchasePrice,
            'quantity' => $this->quantity,
            'admin_id' => $this->admin_id,
            'image' => Storage::disk('public')->url("images/".$this->image),
        ];
    }
}
