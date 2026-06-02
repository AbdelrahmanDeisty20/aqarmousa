<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceService extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'category',
        'image',
    ];

    public function bookings()
    {
        return $this->hasMany(MaintenanceBooking::class);
    }
}
