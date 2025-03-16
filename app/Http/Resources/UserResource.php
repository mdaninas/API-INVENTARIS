<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Env;

class UserResource extends JsonResource
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
            'fullname'=>$this->fullname,
            'username'=>$this->username,
            'email'=>$this->email,
            'image_url'=>asset('storage/' . $this->image_url),
        ];
    }
}
