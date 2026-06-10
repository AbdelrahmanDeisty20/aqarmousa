<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;

class Contact extends Model
{
    //
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'address', 'message', 'unit_id', 'seller_id'];

    protected static function booted(): void
    {
        static::created(function (Contact $contact) {
            $subject = $contact->unit ? "بخصوص العقار ({$contact->unit->title_ar})" : "رسالة عامة";
            ActivityLog::log('رسالة تواصل', "تم إرسال رسالة تواصل جديدة من: {$contact->name} ({$contact->email}) - الموضوع: {$subject}");
        });
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
