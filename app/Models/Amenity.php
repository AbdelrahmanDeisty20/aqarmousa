<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = ['name_ar', 'name_en', 'icon'];

    public function units()
    {
        return $this->belongsToMany(Unit::class);
    }
}
