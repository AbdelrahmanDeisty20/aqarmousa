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

    public static function logVisit(): self
    {
        // Don't log admin assets/requests or livewire updates to keep page_visits clean
        $path = Request::path();
        if (str_starts_with($path, 'admin') || str_starts_with($path, 'livewire') || Request::wantsJson()) {
            return new self();
        }

        return self::create([
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'referer' => Request::header('referer'),
            'user_id' => Auth::id(),
        ]);
    }
}
