<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Table as RestaurantTable;
use App\Models\Employee;
use App\Models\Customer;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Operaciones';
    
    protected static ?int $navigationSort = 3;

    // Configurar búsqueda global
    protected static ?string $recordTitleAttribute = 'order_number';
    
    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Mesa' => $record->table?->number ?? 'Sin mesa',
            'Cliente' => $record->customer?->name ?? 'Sin cliente',
            'Estado' => ucfirst($record->status),
            'Total' => '$' . number_format($record->total, 2),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'order_number',
            'table.number', 
            'customer.name',
            'items.product.name',
            'notes'
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('restaurant_id')
                    ->label('Restaurante')
                    ->options(Restaurant::all()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\Select::make('table_id')
                    ->label('Mesa')
                    ->options(RestaurantTable::all()->pluck('number', 'id'))
                    ->nullable(),
                Forms\Components\Select::make('employee_id')
                    ->label('Empleado')
                    ->options(Employee::all()->pluck('first_name', 'id'))
                    ->nullable(),
                Forms\Components\Select::make('customer_id')
                    ->label('Cliente')
                    ->options(Customer::all()->pluck('name', 'id'))
                    ->nullable(),
                Forms\Components\TextInput::make('order_number')
                    ->label('Número de orden')
                    ->disabled(),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'preparing' => 'Preparando',
                        'ready' => 'Listo',
                        'delivered' => 'Entregado',
                        'cancelled' => 'Cancelado',
                    ])
                    ->required(),
                Forms\Components\Select::make('order_type')
                    ->label('Tipo de orden')
                    ->options([
                        'dine_in' => 'Para comer aquí',
                        'takeaway' => 'Para llevar',
                        'delivery' => 'Delivery',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->numeric(),
                Forms\Components\TextInput::make('tax')
                    ->label('Impuestos')
                    ->numeric(),
                Forms\Components\TextInput::make('total')
                    ->label('Total')
                    ->numeric(),
                Forms\Components\Select::make('payment_status')
                    ->label('Estado de pago')
                    ->options([
                        'pending' => 'Pendiente',
                        'partial' => 'Parcial',
                        'paid' => 'Pagado',
                        'refunded' => 'Reembolsado',
                    ]),
                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Número')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('table.number')
                    ->label('Mesa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('items.product.name')
                    ->label('Productos')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->items->pluck('product.name')->join(', ');
                    }),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'danger' => 'cancelled',
                        'warning' => 'pending',
                        'primary' => 'preparing',
                        'success' => ['ready', 'delivered'],
                    ]),
                Tables\Columns\BadgeColumn::make('order_type')
                    ->label('Tipo')
                    ->colors([
                        'primary' => 'dine_in',
                        'warning' => 'takeaway',
                        'success' => 'delivery',
                    ]),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Pago')
                    ->colors([
                        'danger' => 'pending',
                        'warning' => 'partial',
                        'success' => 'paid',
                        'gray' => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pendiente',
                        'preparing' => 'Preparando',
                        'ready' => 'Listo',
                        'delivered' => 'Entregado',
                        'cancelled' => 'Cancelado',
                    ]),
                Tables\Filters\SelectFilter::make('order_type')
                    ->label('Tipo')
                    ->options([
                        'dine_in' => 'Para comer aquí',
                        'takeaway' => 'Para llevar',
                        'delivery' => 'Delivery',
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

    public static function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\OrderManagementWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
