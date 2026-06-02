<?php

namespace App\Filament\Resources\Viewings\Tables;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Viewing;
use Illuminate\Support\Facades\Notification;

class ViewingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('viewing.columns.user'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit.title')
                    ->label(__('viewing.columns.unit'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('name')
                    ->label(__('viewing.columns.client_name'))
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('viewing.columns.phone'))
                    ->searchable(),
                TextColumn::make('date')
                    ->label(__('viewing.columns.date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('time')
                    ->label(__('viewing.columns.time'))
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('viewing.columns.status'))
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => __('viewing.statuses.pending'),
                        'accepted' => __('viewing.statuses.accepted'),
                        'rejected' => __('viewing.statuses.rejected'),
                        'cancelled' => __('viewing.statuses.cancelled'),
                        'reschedule_admin' => __('viewing.statuses.reschedule_admin'),
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'accepted' => 'success',
                        'rejected', 'cancelled' => 'danger',
                        'reschedule_admin' => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('user_message')
                    ->label(__('viewing.columns.user_message'))
                    ->limit(50)
                    ->tooltip(fn($record) => $record->user_message)
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('created_at')
                    ->label(__('viewing.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->searchPlaceholder(__('viewing.search_placeholder'))
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label(__('viewing.filters.status'))
                    ->options([
                        'pending' => __('viewing.statuses.pending'),
                        'accepted' => __('viewing.statuses.accepted'),
                        'rejected' => __('viewing.statuses.rejected'),
                        'cancelled' => __('viewing.statuses.cancelled'),
                        'reschedule_admin' => __('viewing.statuses.reschedule_admin'),
                    ])
                    ->placeholder(__('viewing.filters.all_statuses')),

                \Filament\Tables\Filters\Filter::make('date_range')
                    ->form([
                        DatePicker::make('date_from')
                            ->label(__('viewing.filters.date_from'))
                            ->placeholder(__('viewing.filters.date_from_placeholder')),
                        DatePicker::make('date_until')
                            ->label(__('viewing.filters.date_until'))
                            ->placeholder(__('viewing.filters.date_until_placeholder')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['date_from'], fn($q, $date) => $q->whereDate('date', '>=', $date))
                            ->when($data['date_until'], fn($q, $date) => $q->whereDate('date', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['date_from'] ?? null) {
                            $indicators[] = \Filament\Tables\Filters\Indicator::make(__('viewing.filters.from') . ' ' . \Carbon\Carbon::parse($data['date_from'])->toFormattedDateString())
                                ->removeField('date_from');
                        }
                        if ($data['date_until'] ?? null) {
                            $indicators[] = \Filament\Tables\Filters\Indicator::make(__('viewing.filters.until') . ' ' . \Carbon\Carbon::parse($data['date_until'])->toFormattedDateString())
                                ->removeField('date_until');
                        }
                        return $indicators;
                    }),

                \Filament\Tables\Filters\SelectFilter::make('user_id')
                    ->label(__('viewing.filters.user'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder(__('viewing.filters.all_users')),

                \Filament\Tables\Filters\SelectFilter::make('unit_id')
                    ->label(__('viewing.filters.unit'))
                    ->relationship('unit', 'title_ar')
                    ->searchable()
                    ->preload()
                    ->placeholder(__('viewing.filters.all_units')),
            ])
            ->actions([
                Action::make('accept')
                    ->label(__('viewing.actions.accept'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(__('viewing.actions.accept_heading'))
                    ->modalDescription(__('viewing.actions.accept_description'))
                    ->modalSubmitActionLabel(__('viewing.actions.confirm_accept'))
                    ->modalCancelActionLabel(__('viewing.actions.cancel_modal'))
                    ->action(function (Viewing $record) {
                        $record->update(['status' => 'accepted']);
                    })
                    ->visible(fn(Viewing $record) => $record->status !== 'accepted'),

                Action::make('reschedule')
                    ->label(__('viewing.actions.propose_new_time'))
                    ->icon('heroicon-o-calendar')
                    ->color('warning')
                    ->modalHeading(__('viewing.actions.propose_heading'))
                    ->modalSubmitActionLabel(__('viewing.actions.send_proposal'))
                    ->modalCancelActionLabel(__('viewing.actions.cancel_modal'))
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('date')
                            ->label(__('viewing.forms.new_date'))
                            ->required()
                            ->default(fn(Viewing $record) => $record->date),
                        \Filament\Forms\Components\TimePicker::make('time')
                            ->label(__('viewing.forms.new_time'))
                            ->required()
                            ->default(fn(Viewing $record) => $record->time),
                    ])
                    ->action(function (Viewing $record, array $data) {
                        $record->update([
                            'date' => $data['date'],
                            'time' => $data['time'],
                            'status' => 'reschedule_admin',
                        ]);
                    }),

                EditAction::make()
                    ->label(__('viewing.actions.edit')),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label(__('viewing.actions.delete_selected')),
                ]),
            ]);
    }
}
