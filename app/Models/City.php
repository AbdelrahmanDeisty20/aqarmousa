<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    use HasFactory;

    protected $fillable = ['name_ar', 'name_en'];

    public function compounds() {
        return $this->hasMany(Compound::class);
    }

    public function units() {
        return $this->hasMany(Unit::class);
    }
}
