<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentUser extends Model
{
    /** @use HasFactory<\Database\Factories\EquipmentUserFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'equipment_id',
        'ngaymuon',
        'status',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
