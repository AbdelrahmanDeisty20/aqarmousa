<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compound extends Model
{
    //
    use HasFactory;

    protected $fillable = ['name_ar', 'name_en', 'description_ar', 'description_en', 'city_id'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
