<?php

namespace App\Filament\Resources\Transactions;

use App\Filament\Resources\Transactions\Pages\CreateTransaction;
use App\Filament\Resources\Transactions\Pages\EditTransaction;
use App\Filament\Resources\Transactions\Pages\ListTransactions;
use App\Filament\Resources\Transactions\Schemas\TransactionForm;
use App\Filament\Resources\Transactions\Tables\TransactionsTable;
use App\Models\Transaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    // Hide from navigation as payment system is not currently needed
    protected static bool $shouldRegisterNavigation = false;


    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.financial');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.transaction');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.transactions');
    }



    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-banknotes';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Transaction::count();
    }

    protected static ?string $recordTitleAttribute = 'transaction_ref';

    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit($record): bool
    {
        return true;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return TransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransactionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTransactions::route('/'),
            'edit' => EditTransaction::route('/{record}/edit'),
        ];
    }
}
