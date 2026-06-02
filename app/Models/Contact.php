<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    //
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'address', 'message', 'unit_id', 'seller_id'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
