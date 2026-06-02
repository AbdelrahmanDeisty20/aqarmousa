<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'name_ar',
        'name_en',
        'email',
        'phone',
        'address',
        'status',
        'logo',
    ];


    public function units() {
        return $this->hasMany(Unit::class, 'developer_id');
    }
}
