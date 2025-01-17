<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GoalResource\Pages;
use App\Filament\Resources\GoalResource\RelationManagers;
use App\Filament\Resources\GoalResource\RelationManagers\TransactionsRelationManager;
use App\Models\Goal;
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GoalResource extends Resource
{
    protected static ?string $model = Goal::class;

    protected static ?string $navigationIcon = 'goal';
    protected static ?string $navigationLabel = 'Obiective';
    protected static ?string $title = 'Obiective';
    protected static ?string $modelLabel = 'obiectiv';

    protected static ?string $pluralModelLabel = 'Obiective';

    protected static ?int $navigationSort = 400;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Denumire')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'sm' => 2,
                            ]),
                        TextInput::make('amount')
                            ->label('Suma obiectiv')
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        DateTimePicker::make('target_date')
                            ->label('Data obiectiv')
                            ->required()
                            ->default(now()->addMonth()),
                        Select::make('currency_code')
                            ->label('Moneda')
                            ->options(country_with_currency_and_symbol())
                            ->default('RON'),
                        ColorPicker::make('color')
                            ->label('Culoare')
                            ->default('#22b3e0'),
                    ])->columns(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ColorColumn::make('color')
                    ->label('Culoare')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Denumire')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Suma obiectiv')
                    ->numeric()
                    ->sortable(),
                BadgeableColumn::make('balance')
                    ->label('Progres')
                    ->suffixBadges([
                        Badge::make('progress')
                            ->label(fn(Model $record) => $record->progress. '%')
                    ]),
                TextColumn::make('target_date')
                    ->label('Data obiectiv')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('currency_code')
                    ->label('Moneda')
                    ->formatStateUsing(fn (string $state): string => country_with_currency_and_symbol($state))
                    ->searchable(),
            ])
            ->filters([
                Filter::make('target_date')
                    ->label('Data obiectiv')
                    ->form([
                        DatePicker::make('target_from')->label(__('goals.fields.target_from')),
                        DatePicker::make('target_until')->label(__('goals.fields.target_until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['target_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('target_date', '>=', $date),
                            )
                            ->when(
                                $data['target_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('target_date', '<=', $date),
                            );
                    }),
            ])
            ->defaultSort('target_date')
            ->actions([
                Action::make('deposit')
                    ->label('Adauga plata')
                    ->icon('lucide-trending-up')
                    ->color('danger')
                    ->form(function(Goal $goal){
                        return (new Pages\ListGoals())->getGoalTransactionFields(goalId: $goal->id);
                    })
                    ->action(function (array $data) {
                        (new Pages\ListGoals())->makeGoalTransaction($data);
                    }),
                Action::make('withdraw')
                    ->label('Retrage plata')
                    ->icon('lucide-trending-down')
                    ->color('warning')
                    ->form(function(Goal $goal){
                        return (new Pages\ListGoals())->getGoalTransactionFields(
                            type: 'withdraw',
                            goalId: $goal->id
                        );
                    })
                    ->action(function (array $data) {
                        (new Pages\ListGoals())->makeGoalTransaction($data);
                    }),
                Tables\Actions\EditAction::make()->slideOver(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->slideOver(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGoals::route('/'),
//            'create' => Pages\CreateGoal::route('/create'),
//            'edit' => Pages\EditGoal::route('/{record}/edit'),
        ];
    }
}
