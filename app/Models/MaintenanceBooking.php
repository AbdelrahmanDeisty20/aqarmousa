<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_service_id',
        'user_id',
        'phone',
        'address',
        'message',
        'status',
    ];

    public function service()
    {
        return $this->belongsTo(MaintenanceService::class, 'maintenance_service_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
