<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;

class Testimonial extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['user_id', 'name', 'position', 'content', 'image', 'status'];

    protected static function booted(): void
    {
        static::created(function (Testimonial $testimonial) {
            $userLabel = $testimonial->user ? $testimonial->user->name : $testimonial->name;
            ActivityLog::log('رأي عميل جديد', "تم إرسال رأي عميل جديد بواسطة ({$userLabel}). الوظيفة: {$testimonial->position}. المحتوى: {$testimonial->content}");
        });

        static::deleted(function (Testimonial $testimonial) {
            $userLabel = $testimonial->user ? $testimonial->user->name : $testimonial->name;
            ActivityLog::log('حذف رأي عميل', "تم حذف رأي العميل المنسوب لـ ({$userLabel})");
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
