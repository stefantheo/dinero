<?php

namespace App\Filament\Resources;

use App\Enums\SpendTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Enums\WalletTypeEnum;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use http\QueryString;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'receipt';
    protected static ?string $navigationLabel = 'Tranzacții';

    protected static ?string $title = 'Tranzacții';
    protected static ?string $modelLabel = 'tranzactie';

    protected static ?string $pluralModelLabel = 'Tranzacții';

    protected static ?int $navigationSort = 600;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns([
                        'sm' => 2,
                    ])->columnSpan([
                        'sm' => 2
                    ])
                    ->schema([
                        Radio::make('type')
                            ->default(TransactionTypeEnum::WITHDRAW->value)
                            ->formatStateUsing(function (string $state, ?Model $record): string {
                                if(!blank($record)) {
                                    if($record->isTransferTransaction) {
                                        return TransactionTypeEnum::TRANSFER->value;
                                    }
                                }
                                return $state;
                            })
                            ->disableOptionWhen(function(?Model $record){
                                if(!blank($record)) {
                                    if($record->isTransferTransaction) {
                                        return true;
                                    }
                                }
                                return false;
                            })
                            ->label('Tip')
                            ->inline()
                            ->required()
                            ->live()
                            ->columnSpan(2)
                            ->options(collect(__('transactions.types'))->pluck('label', 'id')),
                        DateTimePicker::make('happened_at')
                            ->label('Data tranzacției')
                            ->native(false)
                            ->required()
                            ->seconds(false)
                            ->displayFormat('d/m/Y h:i a')
                            ->default(now())
                            ->columnSpan(2),
                        TextInput::make('amount')
                            ->label('Suma tranzacției')
                            ->required()
                            ->disabled(function(?Model $record){
                                if(!blank($record)) {
                                    if($record->isTransferTransaction || $record->isPaymentTransaction) {
                                        return true;
                                    }
                                }
                                return false;
                            })
                            ->autofocus()
                            ->formatStateUsing(function ($state, ?Model $record): string|null {
                                if(!blank($record)) {
                                    return $record->amount_float;
                                }
                                return $state;
                            })
                            ->columnSpan(2)
                            ->numeric(),
                        Textarea::make('description')
                            ->label('Descriere')
                            ->columnSpan(2),
                        Select::make('wallet_id')
                            ->label('Portofel')
                            ->relationship('wallet', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2)
                            ->disabled(function (?Model $record): bool {
                                if(!blank($record)) {
                                    if($record->isTransferTransaction || $record->isPaymentTransaction) {
                                        return true;
                                    }
                                }
                                return false;
                            })
                            ->visible(function (Get $get, ?Model $record): bool {
                                if(!blank($record)) {
                                    if($record->isTransferTransaction || $record->isPaymentTransaction) {
                                        return true;
                                    }

                                    return !blank($record->wallet_id);
                                }
                                return in_array($get('type'), [TransactionTypeEnum::DEPOSIT->value, TransactionTypeEnum::WITHDRAW->value]);
                            }),
                        Select::make('category_id')
                            ->label('Categorie')
                            ->columnSpan(2)
                            ->relationship('category', 'name', function(Builder $query, Get $get){
                                $spendType = match ($get('type')) {
                                    TransactionTypeEnum::WITHDRAW->value => SpendTypeEnum::EXPENSE->value,
                                    TransactionTypeEnum::DEPOSIT->value => SpendTypeEnum::INCOME->value,
                                    default => null,
                                };
                                if(!is_null($spendType)) {
                                    return $query->tenant()->where('type', $spendType);
                                }
                                return $query;
                            })
                            ->default (function () {
                                $category = Transaction::query()
                                    ->where('type', TransactionTypeEnum::WITHDRAW->value)
                                    ->first('category_id');
                                return $category?->category_id;
                            })
                            ->searchDebounce(500)
                            ->selectablePlaceholder(false)
                            ->searchable()
                            ->preload()
                            ->visible(function (Get $get): bool {
                                return in_array($get('type'), [TransactionTypeEnum::DEPOSIT->value, TransactionTypeEnum::WITHDRAW->value]);
                            }),
                        Select::make('from_wallet_id')
                            ->label('Din portofel')
                            ->relationship('wallet', 'name', function(Builder $query, Get $get){
                                if($get('type') == TransactionTypeEnum::PAYMENT->value) {
                                    $query = $query->where('type', WalletTypeEnum::GENERAL->value);
                                }
                                return $query;
                            })
                            ->live()
                            ->columnSpan(function(Get $get, ?Model $record): int {
                                return blank($get('from_wallet_id')) ? 2 : 1;
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->afterStateUpdated(function (Set $set) {
                                $set('to_wallet_id', null);
                            })
                            ->visible(function(Get $get, ?Model $record): bool {
                                if(!blank($record)) {
                                    if($record->isTransferTransaction || $record->isPaymentTransaction) {
                                        return false;
                                    }
                                }
                                return in_array($get('type'), [TransactionTypeEnum::TRANSFER->value, TransactionTypeEnum::PAYMENT->value]);
                            }),
                        Select::make('to_wallet_id')
                            ->label('Către portofel')
                            ->relationship('wallet', 'name', function(Builder $query, Get $get){
                                $query = $query->where('id', '!=', $get('from_wallet_id'));
                                if($get('type') == TransactionTypeEnum::PAYMENT->value) {
                                    $query = $query->where('type', WalletTypeEnum::CREDIT_CARD->value);
                                }
                                return $query;
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible(function (Get $get, ?Model $record): bool {
                                if(!blank($record)) {
                                    if($record->isTransferTransaction || $record->isPaymentTransaction) {
                                        return false;
                                    }
                                }
                                return in_array($get('type'), [TransactionTypeEnum::TRANSFER->value, TransactionTypeEnum::PAYMENT->value]) && !blank($get('from_wallet_id'));
                            }),
                        Toggle::make('confirmed')
                            ->label('Confirmată')
                            ->default(true)
                            ->visible(fn (Get $get): bool => in_array($get('type'), [TransactionTypeEnum::DEPOSIT->value, TransactionTypeEnum::WITHDRAW->value])),

                    ]),
                Forms\Components\Section::make()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        TextInput::make('meta.memo.note')
                            ->label('Nota')
                            ->columnSpan(2),
                        FileUpload::make('meta.memo.attachment')
                            ->label('Atasament')
                            ->columnSpan(2),
                    ]),
            ])->columns([
                'sm' => 3,
                'lg' => null,
            ]);
    }

    public function tableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('happened_at')
                ->label('Data tranzacției')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('type')
                ->badge()
                ->icon(fn (string $state): string => match ($state) {
                    TransactionTypeEnum::WITHDRAW->value => 'lucide-trending-down',
                    TransactionTypeEnum::DEPOSIT->value => 'lucide-trending-up',
                })
                ->color(fn (string $state): string => match ($state) {
                    TransactionTypeEnum::WITHDRAW->value => 'danger',
                    TransactionTypeEnum::DEPOSIT->value => 'warning',
                })
                ->formatStateUsing(fn (string $state): string => __("transactions.types.{$state}.label"))
                ->label('Tip')
                ->searchable(),
            Tables\Columns\TextColumn::make('amount_float')
                ->label('Suma tranzacției')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('wallet.name')
                ->label('Portofel')
                ->weight('bold')
                ->default('—')
                ->color(fn(?Model $record): array => Color::hex(optional($record->wallet)->color ?? '#dcdcdc'))
                ->sortable(),
            Tables\Columns\TextColumn::make('category.name')
                ->label('Categorie')
                ->weight('bold')
                ->default('—')
                ->icon(fn(?Model $record): string => optional($record->category)->icon ?? '')
                ->color(fn(?Model $record): array => Color::hex(optional($record->category)->color ?? '#dcdcdc'))
                ->sortable(),
            Tables\Columns\IconColumn::make('confirmed')
                ->label('Confirmată')
                ->boolean()
                ->trueIcon('lucide-check-circle')
                ->falseIcon('lucide-x-circle'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns((new TransactionResource())->tableColumns())
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(collect(__('transactions.types'))->except([TransactionTypeEnum::TRANSFER->value])->pluck('label', 'id')->toArray()),
                Tables\Filters\SelectFilter::make('wallet_id')
                    ->label('Portofel')
                    ->relationship('wallet', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->deferLoading()
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('happened_at', 'desc')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->slideOver(),
            ]);
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
            'index'  => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit'   => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
