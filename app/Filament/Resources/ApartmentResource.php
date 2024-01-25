<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApartmentResource\Pages;
use App\Filament\Resources\ApartmentResource\RelationManagers;
use App\Models\Apartment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApartmentResource extends Resource

{


    protected static ?string $model = Apartment::class;
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationLabel = 'Apartamente';

    protected static ?string $title = 'Apartamente';
    protected static ?string $modelLabel = 'apartament';
    protected static ?string $pluralModelLabel = 'Apartamente';
    protected static ?int $navigationSort = 100;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Proprietar')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('number')
                    ->numeric()
                    ->label('Numar apartament')
                    ->maxLength(255),
                Forms\Components\TextInput::make('adult_number')
                    ->numeric()
                    ->label('Numar adulti')
                    ->maxLength(255),
                Forms\Components\TextInput::make('children_number')
                    ->numeric()
                    ->label('Numar copii')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Proprietar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number')
                    ->badge()
                    ->label('Numar apartament')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('adult_number')
                    ->label('Numar adulti')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('children_number')
                    ->label('Numar copii')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data creare')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Data actualizare')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->color('primary'),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Actiuni'),
            ],
                position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getWidgets(): array
    {
        return [
            ApartmentResource\Widgets\ApartmentOverview::class,
        ];
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApartments::route('/'),
            'create' => Pages\CreateApartment::route('/create'),
            'edit' => Pages\EditApartment::route('/{record}/edit'),
        ];
    }
}
