<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'success' => true,
            'data' => [
                'id' => $this->id,
                'title' => $this->title,
                'slug' => $this->slug,
                'price' => $this->price,
                'quantity' => $this->quantity,
                'created_at' => $this->created_at,
            ]
        ];
    }

}
