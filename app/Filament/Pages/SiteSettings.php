<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('admin.site_settings');
    }

    public function getTitle(): string
    {
        return __('admin.site_settings');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.content_management');
    }

    public function mount(): void
    {
        $heroImage = Setting::where('key', 'home_hero_image')->first()?->value;
        // Decode if it's a JSON string (multiple images)
        $decodedHeroImage = json_decode($heroImage, true);
        $heroImageValue = is_array($decodedHeroImage) ? $decodedHeroImage : $heroImage;

        $this->data = [
            'home_hero_image' => $heroImageValue,
            'site_name' => Setting::where('key', 'site_name')->first()?->value,
            'site_email' => Setting::where('key', 'site_email')->first()?->value,
            'site_phone' => Setting::where('key', 'site_phone')->first()?->value,
            'site_logo' => Setting::where('key', 'site_logo')->first()?->value,
            'site_address' => Setting::where('key', 'site_address')->first()?->value,
            'social_facebook' => Setting::where('key', 'social_facebook')->first()?->value,
            'social_instagram' => Setting::where('key', 'social_instagram')->first()?->value,
            'social_twitter' => Setting::where('key', 'social_twitter')->first()?->value,
        ];

        $this->form->fill($this->data);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make(__('admin.fields.basic_info'))
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                TextInput::make('site_name')
                                    ->label(__('admin.site_name'))
                                    ->required(),

                                TextInput::make('site_email')
                                    ->label(__('admin.site_email'))
                                    ->email(),

                                TextInput::make('site_phone')
                                    ->label(__('admin.site_phone')),

                                TextInput::make('site_address')
                                    ->label(__('admin.site_address')),
                            ]),

                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                FileUpload::make('site_logo')
                                    ->label(__('admin.site_logo'))
                                    ->helperText('يرجى استخدام صيغ الصور المدعومة: JPG, PNG,JPEG')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png','image/jpg'])
                                    ->directory('settings')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->downloadable()
                                    ->openable(),

                                FileUpload::make('home_hero_image')
                                    ->label(__('admin.home_hero_image'))
                                    ->helperText('يرجى استخدام صيغ الصور المدعومة: JPG, PNG,JPEG')
                                    ->image()
                                    ->multiple()
                                    ->reorderable()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png','image/jpg'])
                                    ->directory('settings')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->downloadable()
                                    ->openable(),
                            ]),
                    ]),

                Section::make(__('admin.social_media'))
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(3)
                            ->schema([
                                TextInput::make('social_facebook')
                                    ->label(__('admin.social_facebook'))
                                    ->url(),
                                TextInput::make('social_instagram')
                                    ->label(__('admin.social_instagram'))
                                    ->url(),
                                TextInput::make('social_twitter')
                                    ->label(__('admin.social_twitter'))
                                    ->url(),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            $heroImages = $data['home_hero_image'];
            if (is_array($heroImages)) {
                $heroImages = json_encode(array_values($heroImages));
            }

            Setting::setValue('home_hero_image', $heroImages, 'image');
            Setting::setValue('site_logo', $data['site_logo'], 'image');
            Setting::setValue('site_name', $data['site_name'], 'text');
            Setting::setValue('site_email', $data['site_email'], 'text');
            Setting::setValue('site_phone', $data['site_phone'], 'text');
            Setting::setValue('site_address', $data['site_address'], 'text');
            Setting::setValue('social_facebook', $data['social_facebook'], 'text');
            Setting::setValue('social_instagram', $data['social_instagram'], 'text');
            Setting::setValue('social_twitter', $data['social_twitter'], 'text');

            Notification::make()
                ->success()
                ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
                ->send();
        } catch (\Exception $exception) {
            Notification::make()
                ->danger()
                ->title(__('admin.notifications.error_saving'))
                ->body($exception->getMessage())
                ->send();
        }
    }
}
