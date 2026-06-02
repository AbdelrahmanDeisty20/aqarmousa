<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitMedia extends Model
{
    //
    use HasFactory;

    protected $fillable = ['unit_id', 'type', 'url', 'order', 'processed_url', 'processing_status'];

    protected static function booted()
    {
        static::creating(function ($media) {
            if ($media->type !== 'video' && empty($media->processing_status)) {
                $media->processing_status = 'completed';
            } elseif ($media->type === 'video' && empty($media->processing_status)) {
                $media->processing_status = 'pending';
            }
        });

        static::created(function ($media) {
            if ($media->type === 'video' && $media->processing_status === 'pending') {
                \App\Jobs\ProcessVideoHLS::dispatch($media);
            }
        });
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
