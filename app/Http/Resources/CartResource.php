<?php

namespace App\Http\Resources;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $items_count = Cart::where('user_id', auth()->id())->count();
        return [
            'id' => $this->id,
            'product' => $this->product,
            'user' => $this->user,
            'items_count' => $items_count,
        ];
    }
}
