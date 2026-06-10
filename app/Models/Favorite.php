<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;

class Favorite extends Model
{
    //
    use HasFactory;

    protected $fillable = ['user_id', 'unit_id'];

    protected static function booted(): void
    {
        static::created(function (Favorite $favorite) {
            $user = $favorite->user;
            $unit = $favorite->unit;
            $userName = $user ? $user->name : 'مستخدم';
            $unitTitle = $unit ? $unit->title_ar : 'عقار';
            ActivityLog::log('إضافة مفضلة', "قام {$userName} بإضافة العقار ({$unitTitle}) إلى مفضلته.");
        });

        static::deleted(function (Favorite $favorite) {
            $user = $favorite->user;
            $unit = $favorite->unit;
            $userName = $user ? $user->name : 'مستخدم';
            $unitTitle = $unit ? $unit->title_ar : 'عقار';
            ActivityLog::log('إزالة مفضلة', "قام {$userName} بإزالة العقار ({$unitTitle}) من مفضلته.");
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
