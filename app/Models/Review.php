<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;

class Review extends Model
{
    //
    use HasFactory;

    protected $fillable = ['user_id', 'unit_id', 'rating', 'comment'];

    protected static function booted(): void
    {
        static::created(function (Review $review) {
            $userLabel = $review->user ? $review->user->name : 'زائر مجهول';
            $unitLabel = $review->unit ? $review->unit->title_ar : "عقار #{$review->unit_id}";
            ActivityLog::log('إضافة تقييم', "قام ({$userLabel}) بتقييم العقار ({$unitLabel}) بـ {$review->rating} نجوم. التعليق: {$review->comment}");
        });

        static::deleted(function (Review $review) {
            $userLabel = $review->user ? $review->user->name : 'زائر مجهول';
            ActivityLog::log('حذف تقييم', "تم حذف تقييم من ({$userLabel}) للعقار #{$review->unit_id}");
        });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function unit() {
        return $this->belongsTo(Unit::class);
    }
}
//test