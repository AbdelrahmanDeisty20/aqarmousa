<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    //
    use HasFactory;

    /**
     * Normalize Arabic string to standard form (remove hamzas, tashkeel, etc.)
     */
    public static function normalizeArabic(?string $text): string
    {
        if (blank($text)) return '';

        // Standardize whitespace
        $text = preg_replace('/\s+/u', ' ', trim($text));

        // Remove tashkeel (diacritics)
        $tashkeel = ['ِ', 'ُ', 'ٓ', 'ٰ', 'ّ', 'ٌ', 'ً', 'ٍ', 'َ', 'ْ'];
        $text = str_replace($tashkeel, '', $text);

        // Standardize Alef
        $text = str_replace(['أ', 'إ', 'آ', 'ٱ'], 'ا', $text);

        // Standardize Teh Marbuta
        $text = str_replace('ة', 'ه', $text);

        // Standardize Ya (Maqsura)
        $text = str_replace(['ى', 'ئ', 'ؤ'], 'ي', $text);

        return $text;
    }

    public static function isRent(?string $text): bool
    {
        if (blank($text)) return false;
        $normalized = static::normalizeArabic($text);
        return in_array(strtolower($normalized), ['rent', 'ايجار']);
    }

    public static function isSale(?string $text): bool
    {
        if (blank($text)) return false;
        $normalized = static::normalizeArabic($text);
        return in_array(strtolower($normalized), ['sale', 'بيع']);
    }

    protected static function booted()
    {
        static::creating(function (Unit $unit) {
            // Ensure imported units (or any unit created without these values)
            // get the desired defaults.
            if (!isset($unit->status) || empty($unit->status)) {
                $unit->status = 'available';
            }

            if (!isset($unit->is_visible)) {
                $unit->is_visible = false;
            }
        });

        static::created(function (Unit $unit) {
            if ($unit->status === 'available') {
                $unit->notifyBuyersInGovernorate();
            }
        });

        static::updated(function (Unit $unit) {
            if ($unit->wasChanged('status') && $unit->status === 'available') {
                $unit->notifyBuyersInGovernorate();
            }
        });
    }

    /**
     * Notify all verified buyers in the same governorate about the new unit.
     */
    public function notifyBuyersInGovernorate()
    {
        /** @var \App\Models\User[] $buyers */
        $buyers = \App\Models\User::where('role', 'buyer')
            ->where('governorate_id', $this->governorate_id)
            ->whereNotNull('email_verified_at')
            ->get();

        foreach ($buyers as $buyer) {
            /** @var \App\Models\User $buyer */
            $buyer->notify(new \App\Notifications\NewUnitAddedNotification($this));
        }
    }

    protected $fillable = [
        'title_ar',
        'title_en',
        'type',
        'description_ar',
        'description_en',
        'address_ar',
        'address_en',
        'price',
        'discount',
        'price_per_m2',
        'offer_type',
        'area',
        'length',
        'width',
        'category', // أرض أم عقار (land / property)
        'rooms',
        'bathrooms',
        'garages',
        'build_year',
        'land_area',
        'internal_area',
        'development_status',
        'status',
        'governorate_id',
        'unit_type_id',
        'compound_id',
        'developer_id',
        'latitude',
        'longitude',
        'sold_at',
        'reserved_at',
        'is_visible',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function compound()
    {
        return $this->belongsTo(Compound::class);
    }

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }

    public function type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function media()
    {
        return $this->hasMany(UnitMedia::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class);
    }

    public function ownership()
    {
        return $this->hasOne(Ownership::class, 'unit_id');
    }

    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        return ($locale === 'ar' ? $this->title_ar : $this->title_en) ?: $this->title_ar;
    }

    public function getAddressAttribute()
    {
        $locale = app()->getLocale();
        return ($locale === 'ar' ? $this->address_ar : $this->address_en) ?: $this->address_ar;
    }

    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return ($locale === 'ar' ? $this->description_ar : $this->description_en) ?: $this->description_ar;
    }

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'sold_at' => 'datetime',
            'reserved_at' => 'datetime',
            'is_visible' => 'boolean',
        ];
    }
}
