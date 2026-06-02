<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('question_ar')->label(__('admin.fields.question_ar'))->required(),
                Textarea::make('answer_ar')->label(__('admin.fields.answer_ar'))->required(),
                TextInput::make('question_en')->label(__('admin.fields.question_en')),
                Textarea::make('answer_en')->label(__('admin.fields.answer_en')),
            ]);
    }
}
