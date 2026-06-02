<?php

namespace App\Filament\Resources\Units\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ImportAction;
use App\Filament\Exports\UnitExporter;
use App\Filament\Imports\UnitImporter;
use App\Filament\Actions\ExcelImportAction;
use Filament\Actions\BulkAction;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class UnitTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('media.url')
                    ->label(__('admin.fields.image'))
                    ->disk('public')
                    ->limit(1)
                    ->circular(),
                TextColumn::make('title_ar')->label(__('admin.fields.title_ar'))->searchable(['title_ar', 'title_en'])->visible(fn() => app()->getLocale() === 'ar'),
                TextColumn::make('title_en')->label(__('admin.fields.title_en'))->searchable(['title_ar', 'title_en'])->visible(fn() => app()->getLocale() === 'en'),
                TextColumn::make('price')->label(__('admin.fields.price'))->money('EGP')->searchable(),
                TextColumn::make('offer_type')
                    ->label(__('admin.fields.offer_type'))
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'sale' => __('admin.fields.offer_types.sale'),
                        'rent' => __('admin.fields.offer_types.rent'),
                        default => $state,
                    }),
                TextColumn::make('city.name_ar')->label(__('admin.resources.city'))->searchable(['name_ar', 'name_en'])->visible(fn() => app()->getLocale() === 'ar'),
                TextColumn::make('city.name_en')->label(__('admin.resources.city'))->searchable(['name_ar', 'name_en'])->visible(fn() => app()->getLocale() === 'en'),
                TextColumn::make('development_status')
                    ->label(__('admin.fields.development_status'))
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => __('admin.fields.development_statuses.' . $state)),
                TextColumn::make('status')->label(__('admin.fields.status'))
                    ->badge()
                    ->colors([
                        // 'warning' => 'pending',
                        'success' => 'approved',
                        // 'danger' => 'rejected',
                        'info' => 'sold',
                        'gray' => 'rented',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        // 'pending' => __('admin.fields.statuses.pending'),
                        'approved' => __('admin.fields.statuses.approved'),
                        // 'rejected' => __('admin.fields.statuses.rejected'),
                        'sold' => __('admin.fields.statuses.sold'),
                        'rented' => __('admin.fields.statuses.rented'),
                        default => $state,
                    })
                    ->searchable(),
                \Filament\Tables\Columns\ToggleColumn::make('is_visible')
                    ->label(__('admin.fields.is_visible'))
                    ->onColor('success')
                    ->offColor('danger'),
                TextColumn::make('sold_at')
                    ->label(__('admin.fields.sold_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rented_at')
                    ->label(__('admin.fields.rented_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])


            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status_filter')
                    ->label(__('admin.fields.status'))
                    ->options([
                        // 'pending' => __('admin.fields.statuses.pending'),
                        'approved' => __('admin.fields.statuses.approved'),
                        // 'rejected' => __('admin.fields.statuses.rejected'),
                        'sold' => __('admin.fields.statuses.sold'),
                        'rented' => __('admin.fields.statuses.rented'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('status', $data['value']);
                        }
                    }),
                \Filament\Tables\Filters\SelectFilter::make('offer_type_filter')
                    ->label(__('admin.fields.offer_type'))
                    ->options([
                        'sale' => __('admin.fields.offer_types.sale'),
                        'rent' => __('admin.fields.offer_types.rent'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('offer_type', $data['value']);
                        }
                    }),
                \Filament\Tables\Filters\SelectFilter::make('city_filter')
                    ->label(__('admin.resources.city'))
                    ->relationship('city', app()->getLocale() === 'ar' ? 'name_ar' : 'name_en'),
                \Filament\Tables\Filters\SelectFilter::make('unit_type_filter')
                    ->label(__('admin.resources.unit_type'))
                    ->relationship('type', app()->getLocale() === 'ar' ? 'name_ar' : 'name_en'),
                \Filament\Tables\Filters\SelectFilter::make('development_status_filter')
                    ->label(__('admin.fields.development_status'))
                    ->options([
                        'primary' => __('admin.fields.development_statuses.primary'),
                        'resale' => __('admin.fields.development_statuses.resale'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('development_status', $data['value']);
                        }
                    }),
            ])

            ->actions([
                EditAction::make(),
                Action::make('approve')
                    ->label(__('admin.actions.approve'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn($record) => $record === null || in_array($record->status, ['approved', 'sold', 'rented']))
                    ->action(fn($record) => $record->update(['status' => 'approved'])),
                Action::make('mark_as_done')
                    ->label(fn($record) => $record->getAttribute('offer_type') === 'sale' ? __('admin.fields.statuses.sold') : __('admin.fields.statuses.rented'))
                    ->icon(fn($record) => $record->getAttribute('offer_type') === 'sale' ? 'heroicon-o-currency-dollar' : 'heroicon-o-key')
                    ->color('info')
                    ->hidden(fn($record) => $record === null || !in_array($record->status, ['approved']))
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->update([
                        'status' => $record->getAttribute('offer_type') === 'sale' ? 'sold' : 'rented',
                        'sold_at' => $record->getAttribute('offer_type') === 'sale' ? now() : $record->sold_at,
                        'rented_at' => $record->getAttribute('offer_type') === 'rent' ? now() : $record->rented_at,
                    ])),
            ])
            ->headerActions([
                \App\Filament\Actions\MultimediaImportAction::make()
                    ->importer(UnitImporter::class)
                    ->label(__('admin.actions.import' ?? 'Import'))
                    ->icon('heroicon-o-document-arrow-up'),


                ExportAction::make()
                    ->exporter(UnitExporter::class)
                    ->label(__('admin.actions.export' ?? 'Export')),

                Action::make('download_template')
                    ->label(__('admin.actions.download_template'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $columns = UnitImporter::getColumns();
                        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();

                        $columnIndex = 1;
                        foreach ($columns as $column) {
                            $headerText = $column->getLabel();
                            $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex) . '1';
                            $sheet->setCellValue($cellCoordinate, $headerText);

                            $commentText = '';
                            $columnName = $column->getName();

                            if ($columnName === 'compound') {
                                $names = \App\Models\Compound::pluck('name_ar')->toArray();
                                $commentText = !empty($names) ? "القيم المتاحة:\n- " . implode("\n- ", $names) : "لا توجد مجمعات سكنية مسجلة.";
                            } elseif ($columnName === 'city') {
                                $names = \App\Models\City::pluck('name_ar')->toArray();
                                $commentText = !empty($names) ? "القيم المتاحة:\n- " . implode("\n- ", $names) : "لا توجد مدن مسجلة.";
                            } elseif ($columnName === 'type') {
                                $names = \App\Models\UnitType::pluck('name_ar')->toArray();
                                $commentText = !empty($names) ? "القيم المتاحة:\n- " . implode("\n- ", $names) : "لا توجد أنواع عقارات مسجلة.";
                            } elseif ($columnName === 'developer') {
                                $names = \App\Models\Developer::pluck('name_ar')->toArray();
                                $commentText = !empty($names) ? "القيم المتاحة:\n- " . implode("\n- ", $names) : "لا يوجد مطورين مسجلين.";
                            } elseif ($columnName === 'offer_type') {
                                $commentText = "القيم المسموحة:\n- بيع\n- إيجار";
                            } elseif ($columnName === 'development_status') {
                                $commentText = "القيم المسموحة:\n- أولي\n- إعادة بيع";
                            } elseif ($columnName === 'status') {
                                $commentText = "القيم المسموحة:\n- مقبول";
                                /*
                            } elseif ($columnName === 'is_visible') {
                                 $commentText = "القيم المسموحة:\n- 1 (مرئي)\n- 0 (مخفي)";
                            */
                            }

                            if (!empty($commentText)) {
                                if (strlen($commentText) > 32000) {
                                    $commentText = substr($commentText, 0, 32000) . '...';
                                }
                                $sheet->getComment($cellCoordinate)->getText()->createTextRun($commentText);
                                $sheet->getComment($cellCoordinate)->setWidth('250pt');
                                $sheet->getComment($cellCoordinate)->setHeight('150pt');
                            }

                            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex))->setAutoSize(true);
                            $columnIndex++;
                        }

                        // Add Examples
                        $columnIndex = 1;
                        foreach ($columns as $column) {
                            $example = $column->getExample() ?? '';
                            $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex) . '2';
                            $sheet->setCellValue($cellCoordinate, $example);
                            $columnIndex++;
                        }

                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                        return response()->streamDownload(function () use ($writer) {
                            $writer->save('php://output');
                        }, 'units-template.xlsx');
                    }),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(UnitExporter::class)
                        ->label(__('admin.actions.export' ?? 'Export')),
                    BulkAction::make('export_pdf')
                        ->label(__('admin.actions.export_pdf' ?? 'Export PDF'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            return response()->streamDownload(function () use ($records) {
                                echo Pdf::loadView('pdf.units', ['units' => $records])->output();
                            }, 'units-export-' . now()->format('Y-m-d') . '.pdf');
                        }),
                ]),
            ]);
    }
}
