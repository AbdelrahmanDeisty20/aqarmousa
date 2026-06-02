<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    use HasFactory;

    protected $fillable = ['user_id', 'unit_id', 'amount', 'payment_status', 'transaction_ref'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function unit() {
        return $this->belongsTo(Unit::class);
    }
}
