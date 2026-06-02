<?php

namespace App\Filament\Actions;

use Filament\Actions\ImportAction;
use Filament\Actions\Imports\ImportColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Csv\Reader as CsvReader;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use OpenSpout\Reader\XLSX\Reader;

class ExcelImportAction extends ImportAction
{
    protected function setUp(): void
    {
        parent::setUp();

        // Override the schema to allow XLSX files
        $this->schema(fn (ImportAction $action): array => array_merge([
            FileUpload::make('file')
                ->label(__('filament-actions::import.modal.form.file.label'))
                ->placeholder(__('filament-actions::import.modal.form.file.placeholder'))
                // Allow Excel MIME types
                ->acceptedFileTypes([
                    'text/csv',
                    'text/plain',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-excel',
                    'application/octet-stream',
                ])
                ->rules($action->getFileValidationRules())
                ->storeFiles(false)
                ->visibility('private')
                ->required()
                ->hiddenLabel()
                ->afterStateUpdated(function (FileUpload $component, Component $livewire, Set $set, ?TemporaryUploadedFile $state) use ($action): void {
                    if (! $state instanceof TemporaryUploadedFile) {
                        return;
                    }

                    try {
                        $livewire->validateOnly($component->getStatePath());
                    } catch (\Illuminate\Validation\ValidationException $exception) {
                        $component->state([]);

                        throw $exception;
                    }

                    $csvStream = $this->getUploadedFileStream($state);

                    if (! $csvStream) {
                        return;
                    }

                    $csvReader = CsvReader::createFromStream($csvStream);

                    if (filled($csvDelimiter = $this->getCsvDelimiter($csvReader))) {
                        $csvReader->setDelimiter($csvDelimiter);
                    }

                    $csvReader->setHeaderOffset($action->getHeaderOffset() ?? 0);

                    $csvColumns = $csvReader->getHeader();

                    $lowercaseCsvColumnValues = array_map(Str::lower(...), $csvColumns);
                    $lowercaseCsvColumnKeys = array_combine(
                        $lowercaseCsvColumnValues,
                        $csvColumns,
                    );

                    $set('columnMap', array_reduce($action->getImporter()::getColumns(), function (array $carry, ImportColumn $column) use ($lowercaseCsvColumnKeys, $lowercaseCsvColumnValues) {
                        $carry[$column->getName()] = $lowercaseCsvColumnKeys[
                        Arr::first(
                            array_intersect(
                                $lowercaseCsvColumnValues,
                                $column->getGuesses(),
                            ),
                        )
                        ] ?? null;

                        return $carry;
                    }, []));
                }),
            Fieldset::make(__('filament-actions::import.modal.form.columns.label'))
                ->columns(1)
                ->inlineLabel()
                ->schema(function (Get $get) use ($action): array {
                    $csvFile = $get('file');

                    if (! $csvFile instanceof TemporaryUploadedFile) {
                        return [];
                    }

                    $csvStream = $this->getUploadedFileStream($csvFile);

                    if (! $csvStream) {
                        return [];
                    }

                    $csvReader = CsvReader::createFromStream($csvStream);

                    if (filled($csvDelimiter = $this->getCsvDelimiter($csvReader))) {
                        $csvReader->setDelimiter($csvDelimiter);
                    }

                    $csvReader->setHeaderOffset($action->getHeaderOffset() ?? 0);

                    $csvColumns = $csvReader->getHeader();
                    $csvColumnOptions = array_combine($csvColumns, $csvColumns);

                    return array_map(
                        fn (ImportColumn $column): Select => $column->getSelect()->options($csvColumnOptions),
                        $action->getImporter()::getColumns(),
                    );
                })
                ->statePath('columnMap')
                ->visible(fn (Get $get): bool => $get('file') instanceof TemporaryUploadedFile),
        ], $action->getImporter()::getOptionsFormComponents()));
    }

    public function getFileValidationRules(): array
    {
        return [
            'extensions:csv,txt,xlsx,xls',
        ];
    }

    public function getUploadedFileStream(TemporaryUploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();

        if (in_array(strtolower($extension), ['xlsx', 'xls'])) {
            return $this->convertExcelToCsvStream($file);
        }

        return parent::getUploadedFileStream($file);
    }

    protected function convertExcelToCsvStream(TemporaryUploadedFile $file)
    {
        $reader = new Reader();
        $reader->open($file->getRealPath());

        $tempStream = fopen('php://temp', 'r+');

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                // Write row to CSV stream
                $cells = array_map(function ($value) {
                    if (!is_string($value)) {
                        return $value;
                    }
                    // Deep trim: remove standard whitespace and UTF-8 non-breaking spaces/control characters
                    return preg_replace('/^[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+|[\s\p{Zs}\p{Zl}\p{Zp}\x{00a0}]+$/u', '', $value);
                }, $row->toArray());

                fputcsv($tempStream, $cells);
            }
            // Only process the first sheet
            break;
        }

        $reader->close();

        rewind($tempStream);
        return $tempStream;
    }
}