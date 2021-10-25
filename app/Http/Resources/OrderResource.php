<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
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
                'status' => $this->status,
                'total_price' => $this->total_price,
                'payment_method' => $this->payment_method,
                'order_products'=>$this->orderProducts

            ]
        ];
    }
}
