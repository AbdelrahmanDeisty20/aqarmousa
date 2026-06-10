<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class PageVisit extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'url',
        'referer',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function logVisit(): ?self
    {
        $path = Request::path();
        $ip = Request::ip();

        // استثناء مسارات لوحة التحكم وأكواد الخلفية
        if (
            str_starts_with($path, 'admin') ||
            str_starts_with($path, 'livewire') ||
            str_starts_with($path, '_filament') ||
            str_starts_with($path, 'up')
        ) {
            return null;
        }

        // لمنع تكرار تسجيل الزيارة لنفس الشخص بسبب طلبات الـ API المتعددة المتزامنة في الـ React
        // نقوم بالتحقق إذا كان نفس الـ IP قد سجل زيارة في آخر دقيقة
        $recentVisit = self::where('ip_address', $ip)
            ->where('created_at', '>=', now()->subMinute())
            ->exists();

        if ($recentVisit) {
            return null;
        }

        return self::create([
            'ip_address' => $ip,
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'referer' => Request::header('referer'),
            'user_id' => Auth::id(),
        ]);
    }
}
