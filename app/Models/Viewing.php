<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;

class Viewing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'unit_id',
        'name',
        'email',
        'phone',
        'date',
        'time',
        'status',
        'notes',
        'user_message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    protected static function booted()
    {
        static::created(function (Viewing $record) {
            $unit = $record->unit;
            $unitTitle = $unit ? $unit->title_ar : 'عقار';
            ActivityLog::log('طلب معاينة', "تم تقديم طلب معاينة جديد للعقار ({$unitTitle}) من قبل: {$record->name} ({$record->email}) بتاريخ {$record->date} في تمام {$record->time}");
        });

        static::updated(function (Viewing $record) {
            $oldStatus = $record->getOriginal('status');
            if ($record->status !== $oldStatus) {
                ActivityLog::log('تحديث حالة المعاينة', "تم تحديث حالة طلب المعاينة الخاص بـ ({$record->name}) إلى: {$record->status}");
            }
        });

        static::saved(function (Viewing $record) {
            $oldStatus = $record->getOriginal('status');
            $oldDate = $record->getOriginal('date');
            $oldTime = $record->getOriginal('time');

            // 1. Status changed to accepted
            if ($record->status === 'accepted' && $oldStatus !== 'accepted') {
                // Send Database Notification to Registered User (if exists)
                if ($record->user) {
                    $record->user->notify(new \App\Notifications\ViewingStatusNotification($record, 'accepted', ['database']));
                }

                // Send Email Notification to Viewing Email
                if ($record->email) {
                    try {
                        \Illuminate\Support\Facades\Notification::route('mail', $record->email)
                            ->notify(new \App\Notifications\ViewingStatusNotification($record, 'accepted', ['mail']));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Error sending accepted email: ' . $e->getMessage());
                    }
                }
            }

            // 1.1 Status changed to rejected
            if ($record->status === 'rejected' && $oldStatus !== 'rejected') {
                // Send Database Notification to Registered User (if exists)
                if ($record->user) {
                    $record->user->notify(new \App\Notifications\ViewingStatusNotification($record, 'rejected', ['database']));
                }

                // Send Email Notification to Viewing Email
                if ($record->email) {
                    try {
                        \Illuminate\Support\Facades\Notification::route('mail', $record->email)
                            ->notify(new \App\Notifications\ViewingStatusNotification($record, 'rejected', ['mail']));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Error sending rejected email: ' . $e->getMessage());
                    }
                }
            }

            // 2. Status is reschedule_admin and (status just changed OR date/time changed)
            $isRescheduled = $record->status === 'reschedule_admin' && (
                $oldStatus !== 'reschedule_admin' ||
                $oldDate != $record->date ||
                $oldTime != $record->time
            );

            if ($isRescheduled) {
                // Send Database Notification to Registered User (if exists)
                if ($record->user) {
                    $record->user->notify(new \App\Notifications\ViewingStatusNotification($record, 'reschedule_admin', ['database']));
                }

                // Send Email Notification to Viewing Email
                if ($record->email) {
                    try {
                        \Illuminate\Support\Facades\Notification::route('mail', $record->email)
                            ->notify(new \App\Notifications\ViewingStatusNotification($record, 'reschedule_admin', ['mail']));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Error sending reschedule email: ' . $e->getMessage());
                    }
                }
            }
        });
    }
}
