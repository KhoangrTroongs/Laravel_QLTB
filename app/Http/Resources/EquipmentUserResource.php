<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentUserResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'equipment' => new EquipmentResource($this->whenLoaded('equipment')),
            'ngaymuon' => $this->ngaymuon ? Carbon::parse($this->ngaymuon)->format('d/m/Y H:i') : null,
            'hantra' => $this->hantra ? Carbon::parse($this->hantra)->format('d/m/Y') : null,
            'ngaytra' => $this->ngaytra ? Carbon::parse($this->ngaytra)->format('d/m/Y') : null,
            'status' => $this->status,
            'status_label' => match ($this->status) {
                0 => 'Chờ duyệt',
                1 => 'Đang mượn',
                2 => 'Từ chối',
                3 => 'Đã trả',
                default => 'Không xác định',
            },
            'description' => $this->description,
            'is_overdue' => $this->status === 1 && $this->hantra && $this->hantra < now(),
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
        ];
    }
}
