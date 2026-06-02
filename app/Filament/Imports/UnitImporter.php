<?php

namespace App\Filament\Imports;

use App\Models\Unit;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Log;

class UnitImporter extends Importer
{
    protected static ?string $model = Unit::class;

    /**
     * Normalize Arabic string to standard form (remove hamzas, tashkeel, etc.)
     */
    protected static function normalizeArabic(?string $text): string
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

    protected static function isRent(?string $text): bool
    {
        if (blank($text)) return false;
        $normalized = static::normalizeArabic($text);
        return in_array(strtolower($normalized), ['rent', 'ايجار']);
    }

    protected static function isSale(?string $text): bool
    {
        if (blank($text)) return false;
        $normalized = static::normalizeArabic($text);
        return in_array(strtolower($normalized), ['sale', 'بيع']);
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title_ar')
                ->label('العنوان (عربي)')
                ->guess(['العنوان (عربي)', 'العنوان', 'title_ar'])
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('شقة فاخرة للبيع في المعادي'),
            ImportColumn::make('title_en')
                ->label('العنوان (إنجليزي) (اختياري)')
                ->guess(['العنوان (إنجليزي) (اختياري)', 'العنوان (إنجليزي)', 'title_en'])
                ->rules(['nullable', 'max:255'])
                ->example('Luxury Apartment for Sale in Maadi'),
            ImportColumn::make('description_ar')
                ->label('الوصف (عربي)')
                ->guess(['الوصف (عربي)', 'الوصف', 'description_ar'])
                ->requiredMapping()
                ->rules(['required'])
                ->example('شقة 3 غرف نوم وصالة كبيرة...'),
            ImportColumn::make('description_en')
                ->label('الوصف (إنجليزي) (اختياري)')
                ->guess(['الوصف (إنجليزي) (اختياري)', 'الوصف (إنجليزي)', 'description_en'])
                ->rules(['nullable'])
                ->example('3 Bedroom apartment with large hall...'),
            ImportColumn::make('address_ar')
                ->label('العنوان بالتفصيل (عربي)')
                ->guess(['العنوان بالتفصيل (عربي)', 'العنوان بالتفصيل', 'العنوان بالكامل', 'address_ar', 'address'])
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('15 شارع النصر، المعادي'),
            ImportColumn::make('address_en')
                ->label('العنوان بالتفصيل (إنجليزي) (اختياري)')
                ->guess(['العنوان بالتفصيل (إنجليزي) (اختياري)', 'العنوان بالتفصيل (إنجليزي)', 'address_en'])
                ->rules(['nullable', 'max:255'])
                ->example('15 El Nasr St, Maadi'),
            ImportColumn::make('price')
                ->label('السعر')
                ->guess(['السعر', 'سعر', 'price'])
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'numeric'])
                ->example('5000000'),
            ImportColumn::make('price_per_m2')
                ->label('سعر المتر (اختياري)')
                ->guess(['سعر المتر (اختياري)', 'سعر المتر', 'price_per_m2'])
                ->rules(['nullable', function ($attribute, $value, $fail) {
                    $cleaned = is_string($value) ? preg_replace('/^[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+|[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+$/u', '', $value) : $value;
                    if (blank($cleaned)) return;
                    if (!is_numeric($cleaned)) {
                        $fail('يجب أن يكون الحقل ' . $attribute . ' رقمًا.');
                    }
                }])
                ->example('25000'),
            ImportColumn::make('offer_type')
                ->label('نوع العرض')
                ->guess(['نوع العرض (sale/rent)', 'نوع العرض', 'offer_type'])
                ->requiredMapping()
                ->castStateUsing(function (string $state): string {
                    if (static::isRent($state)) return 'rent';
                    if (static::isSale($state)) return 'sale';
                    return (strtolower($state) === 'rent' || strtolower($state) === 'sale') ? strtolower($state) : $state;
                })
                ->rules(['required', 'in:sale,rent'])
                ->example('بيع'),
            ImportColumn::make('area')
                ->label('المساحة')
                ->guess(['المساحة', 'مساحة', 'area'])
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'numeric'])
                ->example('200'),
            ImportColumn::make('rooms')
                ->label('الغرف (اختياري)')
                ->guess(['الغرف (اختياري)', 'الغرف', 'rooms'])
                ->rules(['nullable', function ($attribute, $value, $fail) {
                    $cleaned = is_string($value) ? preg_replace('/^[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+|[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+$/u', '', $value) : $value;
                    if (blank($cleaned)) return;
                    if (!is_numeric($cleaned)) {
                        $fail('يجب أن يكون الحقل ' . $attribute . ' عددًا صحيحًا.');
                    }
                }])
                ->example('3'),
            ImportColumn::make('bathrooms')
                ->label('الحمامات (اختياري)')
                ->guess(['الحمامات (اختياري)', 'الحمامات', 'bathrooms'])
                ->rules(['nullable', function ($attribute, $value, $fail) {
                    $cleaned = is_string($value) ? preg_replace('/^[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+|[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+$/u', '', $value) : $value;
                    if (blank($cleaned)) return;
                    if (!is_numeric($cleaned)) {
                        $fail('يجب أن يكون الحقل ' . $attribute . ' عددًا صحيحًا.');
                    }
                }])
                ->example('2'),
            ImportColumn::make('garages')
                ->label('الجراجات (اختياري)')
                ->guess(['الجراجات (اختياري)', 'الجراجات', 'garages'])
                ->rules(['nullable', function ($attribute, $value, $fail) {
                    $cleaned = is_string($value) ? preg_replace('/^[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+|[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+$/u', '', $value) : $value;
                    if (blank($cleaned)) return;
                    if (!is_numeric($cleaned)) {
                        $fail('يجب أن يكون الحقل ' . $attribute . ' عددًا صحيحًا.');
                    }
                }])
                ->example('1'),
            ImportColumn::make('build_year')
                ->label('سنة البناء (اختياري)')
                ->guess(['سنة البناء (اختياري)', 'سنة البناء', 'build_year'])
                ->rules(['nullable'])
                ->example('2023'),
            ImportColumn::make('land_area')
                ->label('مساحة الأرض (اختياري)')
                ->guess(['مساحة الأرض (اختياري)', 'مساحة الأرض', 'land_area'])
                ->rules(['nullable', function ($attribute, $value, $fail) {
                    $cleaned = is_string($value) ? preg_replace('/^[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+|[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+$/u', '', $value) : $value;
                    if (blank($cleaned)) return;

                    if (!is_numeric($cleaned)) {
                        $fail('يجب أن يكون الحقل ' . $attribute . ' رقمًا. القيمة الحالية: ' . $value);
                    }
                }])
                ->example('0'),
            ImportColumn::make('internal_area')
                ->label('المساحة الداخلية (اختياري)')
                ->guess(['المساحة الداخلية (اختياري)', 'المساحة الداخلية', 'internal_area'])
                ->rules(['nullable', function ($attribute, $value, $fail) {
                    $cleaned = is_string($value) ? preg_replace('/^[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+|[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+$/u', '', $value) : $value;
                    if (blank($cleaned)) return;

                    if (!is_numeric($cleaned)) {
                        $fail('يجب أن يكون الحقل ' . $attribute . ' رقمًا. القيمة الحالية: ' . $value);
                    }
                }])
                ->example('180'),
            /*
            ImportColumn::make('is_visible')
                ->label('مرئي للجمهور')
                ->guess(['مرئي للجمهور (1 أو 0)', 'مرئي للجمهور', 'is_visible'])
                ->requiredMapping()
                ->castStateUsing(function (string $state): int {
                    $state = trim($state);
                    return match ($state) {
                        'نعم', 'مرئي', '1' => 1,
                        'لا', 'مخفي', '0' => 0,
                        default => (int) $state,
                    };
                })
                ->boolean()
                ->rules(['required', 'boolean'])
                ->example('0'),
*/
            ImportColumn::make('development_status')
                ->label('حالة التطوير')
                ->guess(['حالة التطوير (primary/resale) (اختياري)', 'حالة التطوير', 'development_status'])
                ->castStateUsing(function (?string $state): ?string {
                    if (blank($state)) return null;

                    $normalized = static::normalizeArabic($state);
                    $normalized = strtolower($normalized);

                    return match ($normalized) {
                        'اولي', 'جديد', 'اول', 'primary' => 'primary',
                        'اعاده بيع', 'resale' => 'resale',
                        default => (strtolower($state) === 'primary' || strtolower($state) === 'resale') ? strtolower($state) : $state,
                    };
                })
                ->rules(['nullable', 'max:255', 'in:primary,resale'])
                ->example('أولي'),
            /*
            ImportColumn::make('status')
                ->label('الحالة')
                ->guess(['الحالة (approved/pending) (اختياري)', 'الحالة', 'status'])
                ->castStateUsing(function (?string $state): ?string {
                    if (blank($state)) return null;
                    $state = trim($state);
                    return match ($state) {
                        'مقبول', 'موافقة', 'تم الموافقة', 'approved' => 'approved',
                        // 'قيد الانتظار', 'انتظار', 'pending' => 'pending',
                        // 'مرفوض', 'rejected' => 'rejected',
                        default => $state,
                    };
                })
                ->rules(['nullable', 'in:approved,pending'])
                ->example('مقبول'),
*/

            // البحث عن المدينة باسمها العربي
            ImportColumn::make('city')
                ->label('المدينة')
                ->guess(['المدينة', 'مدينة', 'city'])
                ->relationship(resolveUsing: 'name_ar')
                ->requiredMapping()
                ->rules(['required'])
                ->example('القاهرة'),

            // البحث عن نوع الوحدة باسمها العربي
            ImportColumn::make('type')
                ->label('نوع العقار')
                ->guess(['نوع العقار', 'النوع', 'type'])
                ->relationship(resolveUsing: 'name_ar')
                ->requiredMapping()
                ->rules(['required'])
                ->example('شقة'),

            // البحث عن المجمع السكني (الكمبوند) باسمه
            ImportColumn::make('compound')
                ->label('الكمبوند (اختياري)')
                ->guess(['الكمبوند (اختياري)', 'الكمبوند', 'المجمع السكني', 'compound'])
                ->relationship(resolveUsing: 'name_ar')
                ->example('بالم هيلز الإسكندرية'),

            // البحث عن المطور العقاري باسمه
            ImportColumn::make('developer')
                ->label('المطور العقاري (اختياري)')
                ->guess(['المطور العقاري (اختياري)', 'المطور العقاري', 'المطور', 'developer'])
                ->relationship(resolveUsing: 'name_ar')
                ->example('إعمار مصر'),

            ImportColumn::make('latitude')
                ->label('خط العرض (اختياري)')
                ->guess(['خط العرض (اختياري)', 'خط العرض', 'latitude'])
                ->rules(['nullable', function ($attribute, $value, $fail) {
                    $cleaned = is_string($value) ? preg_replace('/^[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+|[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+$/u', '', $value) : $value;
                    if (blank($cleaned)) return;
                    if (!is_numeric($cleaned)) {
                        $fail('يجب أن يكون الحقل ' . $attribute . ' رقمًا.');
                    }
                }])
                ->example('30.0444'),
            ImportColumn::make('longitude')
                ->label('خط الطول (اختياري)')
                ->guess(['خط الطول (اختياري)', 'خط الطول', 'longitude'])
                ->rules(['nullable', function ($attribute, $value, $fail) {
                    $cleaned = is_string($value) ? preg_replace('/^[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+|[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+$/u', '', $value) : $value;
                    if (blank($cleaned)) return;
                    if (!is_numeric($cleaned)) {
                        $fail('يجب أن يكون الحقل ' . $attribute . ' رقمًا.');
                    }
                }])
                ->example('31.2357'),
        ];
    }

    public function resolveRecord(): Unit
    {
        Log::info('UnitImporter resolveRecord - Starting', [
            'row_data' => $this->data,
        ]);

        $unit = new Unit();

        // Set current user as owner from the import record
        $unit->owner_id = $this->import->user_id;
        $unit->is_visible = false; // Default to hidden (User requested)

        Log::info('UnitImporter resolveRecord - Unit created with defaults', [
            'status' => $unit->status,
            'is_visible' => $unit->is_visible,
            'owner_id' => $unit->owner_id,
        ]);

        return $unit;
    }

    protected function beforeFill(): void
    {
        Log::info('UnitImporter beforeFill - Starting', [
            'data_before' => $this->data,
        ]);

        // Force set default values in the data array before filling the model
        $this->data['status'] = 'approved';
        $this->data['is_visible'] = false;

        // If offer_type is rent, development_status MUST be empty
        if (static::isRent($this->data['offer_type'] ?? '')) {
            Log::info('UnitImporter beforeFill - Detected RENT, clearing development_status');
            $this->data['development_status'] = null;
        }

        Log::info('UnitImporter beforeFill - After setting defaults', [
            'status' => $this->data['status'] ?? null,
            'is_visible' => $this->data['is_visible'] ?? null,
            'development_status' => $this->data['development_status'] ?? 'NOT_CLEARED',
        ]);
    }

    protected function beforeSave(): void
    {
        Log::info('UnitImporter beforeSave - Trace', [
            'offer_type' => $this->record->offer_type,
            'development_status' => $this->record->development_status,
            'is_rent' => static::isRent($this->record->offer_type),
        ]);

        // Force set these values to ensure they are correct
        $this->record->is_visible = false;
        $this->record->status = 'approved';

        // FORCE clear development_status if offer_type is rent
        if (static::isRent($this->record->offer_type)) {
            Log::info('UnitImporter beforeSave - FORCE clearing development_status for RENT');
            $this->record->development_status = null;
            $this->record->setAttribute('development_status', null);
        }

        Log::info('UnitImporter beforeSave - Final record state', [
            'status' => $this->record->status,
            'is_visible' => $this->record->is_visible,
            'offer_type' => $this->record->offer_type,
            'development_status' => $this->record->development_status,
        ]);
    }

    protected function afterSave(): void
    {
        $unit = $this->record;

        Log::info('UnitImporter afterSave - Record saved', [
            'unit_id' => $unit->id,
            'status' => $unit->status,
            'is_visible' => $unit->is_visible,
        ]);

        // Handle Images
        if (!empty($this->data['images'])) {
            $mediaItems = array_map('trim', explode(',', $this->data['images']));

            // Handle options (decode if string)
            $options = $this->options;
            if (is_string($options)) {
                $options = json_decode($options, true);
            }
            $imagesSourcePath = $options['images_source_path'] ?? null;

            if ($imagesSourcePath && is_dir($imagesSourcePath)) {
                foreach ($mediaItems as $mediaItem) {
                    // Determine type and filename
                    $type = 'image';
                    $filename = $mediaItem;

                    if (str_starts_with($mediaItem, 'video:')) {
                        $type = 'video';
                        $filename = substr($mediaItem, 6);
                    } elseif (str_starts_with($mediaItem, '3d:')) {
                        $type = '3d';
                        $filename = substr($mediaItem, 3);
                    } elseif (str_starts_with($mediaItem, 'floorplan:')) {
                        $type = 'floorplan';
                        $filename = substr($mediaItem, 10);
                    }

                    $filename = trim($filename);
                    $sourceFile = $imagesSourcePath . DIRECTORY_SEPARATOR . $filename;

                    if (file_exists($sourceFile)) {
                        // Copy to media destination
                        // We use a unique name to avoid conflicts
                        $newFilename = uniqid('unit_' . $unit->id . '_') . '_' . $filename;
                        $destinationPath = 'units/media/' . $newFilename;

                        // Storage::disk('public')->put() requires content, but copy is better.
                        // But source is absolute path, destination is relative to disk.
                        // We can use php copy() to the disk's full path.

                        $disk = \Illuminate\Support\Facades\Storage::disk('public');
                        $fullDestPath = $disk->path($destinationPath);

                        // Ensure directory exists
                        if (!file_exists(dirname($fullDestPath))) {
                            mkdir(dirname($fullDestPath), 0755, true);
                        }

                        if (copy($sourceFile, $fullDestPath)) {
                            // Create UnitMedia record
                            \App\Models\UnitMedia::create([
                                'unit_id' => $unit->id,
                                'type' => $type,
                                'url' => $destinationPath,
                                'processing_status' => $type === 'video' ? 'pending' : 'completed',
                            ]);
                        }
                    }
                }
            }
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'تم الانتهاء من استيراد الوحدات بنجاح. تم إضافة ' . Number::format($import->successful_rows) . ' وحدة.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' وفشل استيراد ' . Number::format($failedRowsCount) . ' وحدة بسبب أخطاء في البيانات.';
        }

        return $body;
    }
}
