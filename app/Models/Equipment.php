<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    /** @use HasFactory<\Database\Factories\EquipmentFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'model',
        'image',
        'description',
        'status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'equipment_users')
                ->withPivot('ngaymuon', 'status', 'description')
                ->withTimestamps();
    }
}
