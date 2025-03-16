<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'item_name'=>$this->item_name,
            'description'=>$this->description,
            'stock'=>$this->stock,
            'price'=>$this->price,
            'total_price'=>$this->total_price,
            "id_user" => $this->id_user,
            'image_url'=>asset('storage/' . $this->image_url),
        ];
    }
}
