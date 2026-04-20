<?php

namespace App\Models;

use Database\Factories\EquipmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    /** @use HasFactory<EquipmentFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'model',
        'image',
        'description',
        'status',
        'available',
        'category_id',
        'spec',
    ];

    protected function casts(): array
    {
        return [
            'spec' => 'array',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'equipment_users')
            ->withPivot('id', 'ngaymuon', 'hantra', 'ngaytra', 'status', 'description')
            ->withTimestamps();
    }

    public function borrowRecords()
    {
        return $this->hasMany(EquipmentUser::class);
    }
}
