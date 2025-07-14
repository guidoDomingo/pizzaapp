<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TableResource\Pages;
use App\Models\Table as RestaurantTable;
use App\Models\Restaurant;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class TableResource extends Resource
{
    protected static ?string $model = RestaurantTable::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Gestión';

    protected static ?string $modelLabel = 'Mesa';

    protected static ?string $pluralModelLabel = 'Mesas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('restaurant_id')
                    ->label('Restaurante')
                    ->options(Restaurant::all()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('number')
                    ->label('Número de Mesa')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('capacity')
                    ->label('Capacidad')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'available' => 'Disponible',
                        'occupied' => 'Ocupada',
                        'reserved' => 'Reservada',
                        'maintenance' => 'Mantenimiento',
                    ])
                    ->required()
                    ->default('available'),
                Forms\Components\TextInput::make('qr_code')
                    ->label('Código QR')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Número')
                    ->searchable(),
                Tables\Columns\TextColumn::make('restaurant.name')
                    ->label('Restaurante')
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('Capacidad')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'success' => 'available',
                        'danger' => 'occupied',
                        'warning' => 'reserved',
                        'gray' => 'maintenance',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'available' => 'Disponible',
                        'occupied' => 'Ocupada',
                        'reserved' => 'Reservada',
                        'maintenance' => 'Mantenimiento',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTables::route('/'),
            'create' => Pages\CreateTable::route('/create'),
            'edit' => Pages\EditTable::route('/{record}/edit'),
        ];
    }
}
