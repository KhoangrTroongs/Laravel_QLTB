<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
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
            'name' => $this->name,
            'model' => $this->model,
            'description' => $this->description,
            'status' => $this->status,
            'available' => $this->available,
            'spec' => $this->spec,
            'image' => $this->image ? asset('storage/'.$this->image) : null,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'is_borrowed' => $this->when(
                isset($this->active_borrow_count),
                fn () => $this->active_borrow_count > 0
            ),
            'deleted_at' => $this->deleted_at?->format('d/m/Y'),
            'created_at' => $this->created_at?->format('d/m/Y'),
        ];
    }
}
