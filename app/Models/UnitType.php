<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitType extends Model
{
    //
    use HasFactory;

    protected $fillable = ['name_ar', 'name_en'];

    public function units()
    {
        return $this->hasMany(Unit::class, 'unit_type_id');
    }
}
