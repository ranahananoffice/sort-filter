<?php

namespace App\Http\Resources\Api\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceSortResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'productId' => $this->id,
            'image' => $this->image,
            'tile' => $this->title,
            'tag' => $this->tag,
            'discription' => $this->discription,
            'price' => $this->originalPrice,
            'discountPrice' => $this->discountPrice,
            'isTopSeller'=> $this->isTopSeller,

        ];
    }
}
