<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compound extends Model
{
    //
    use HasFactory;

    protected $fillable = ['name_ar', 'name_en', 'description_ar', 'description_en', 'governorate_id'];

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
