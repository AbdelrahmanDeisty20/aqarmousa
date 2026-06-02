<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use App\Filament\Exports\UserExporter;
use Filament\Actions\BulkAction;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('avatar')
                    ->label(__('admin.fields.image'))
                    ->disk('public')
                    ->circular(),
                TextColumn::make('name')->label(__('admin.fields.name'))->searchable(),
                TextColumn::make('email')->label(__('admin.fields.email'))->searchable(),
                TextColumn::make('role')->label(__('admin.fields.role'))
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => __("admin.fields.roles.{$state}")),
                TextColumn::make('status')->label(__('admin.fields.status'))
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn(string $state): string => __("admin.fields.statuses.{$state}")),
                TextColumn::make('email_verified_at')->label(__('admin.fields.email_status'))
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->email_verified_at !== null ? 'verified' : 'unverified')
                    ->formatStateUsing(fn (string $state): string => __('admin.fields.statuses.' . $state))
                    ->colors([
                        'success' => 'verified',
                        'warning' => 'unverified',
                    ]),
                TextColumn::make('phone')->label(__('admin.fields.phone')),
                TextColumn::make('city.name_ar')->label(__('admin.fields.city')),
                TextColumn::make('address')->label(__('admin.fields.address'))->limit(30),
            //     \Filament\Tables\Columns\ImageColumn::make('id_photo')
            //         ->label(__('admin.fields.id_photo'))
            //         ->disk('public')
            //         ->square()
            //         ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('role_filter')
                    ->label(__('admin.fields.role'))
                    ->options([
                        'admin' => __('admin.fields.roles.admin'),
                        'buyer' => __('admin.fields.roles.buyer'),
                        // 'seller' => __('admin.fields.roles.seller'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('role', $data['value']);
                        }
                    }),
                // \Filament\Tables\Filters\SelectFilter::make('status_filter')
                //     ->label(__('admin.fields.status'))
                //     ->options([
                //         // 'pending' => __('admin.fields.statuses.pending'),
                //         'approved' => __('admin.fields.statuses.approved'),
                //         // 'rejected' => __('admin.fields.statuses.rejected'),
                //     ])
                //     ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                //         if ($data['value']) {
                //             $query->where('status', $data['value']);
                //         }
                //     }),
                \Filament\Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label(__('admin.fields.email_status'))
                    ->placeholder(__('admin.fields.statuses.all_statuses'))
                    ->trueLabel(__('admin.fields.statuses.verified'))
                    ->falseLabel(__('admin.fields.statuses.unverified'))
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('email_verified_at'),
                        false: fn ($query) => $query->whereNull('email_verified_at'),
                    ),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('verify_email')
                    ->label(__('admin.actions.verify_email'))
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->hidden(fn($record) => $record === null || $record->email_verified_at !== null)
                    ->action(function ($record) {
                        $record->update(['email_verified_at' => now()]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading(__('admin.actions.verify_email'))
                    ->modalDescription(fn($record) => __('admin.fields.email_verified_helper')),
                Action::make('approve_seller')
                    ->label(__('admin.actions.approve_seller'))
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->hidden(fn($record) => $record === null || $record->status === 'approved' || $record->role !== 'seller')
                    ->action(fn($record) => $record->update(['status' => 'approved'])),
                Action::make('reject_seller')
                    ->label(__('admin.actions.reject_seller'))
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->hidden(fn($record) => $record === null || $record->status === 'rejected' || $record->role !== 'seller')
                    ->action(fn($record) => $record->update(['status' => 'rejected'])),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(UserExporter::class)
                    ->label(__('admin.actions.export' ?? 'Export')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(UserExporter::class)
                        ->label(__('admin.actions.export' ?? 'Export')),
                    BulkAction::make('export_pdf')
                        ->label(__('admin.actions.export_pdf' ?? 'Export PDF'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            return response()->streamDownload(function () use ($records) {
                                echo Pdf::loadView('pdf.users', ['users' => $records])->output();
                            }, 'users-export-' . now()->format('Y-m-d') . '.pdf');
                        }),
                ]),
            ]);
    }
}