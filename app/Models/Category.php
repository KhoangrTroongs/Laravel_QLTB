<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'specs'];

    protected function casts(): array
    {
        return [
            'specs' => 'array',
        ];
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }
}
