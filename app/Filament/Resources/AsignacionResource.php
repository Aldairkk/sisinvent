<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsignacionResource\Pages;
use App\Filament\Resources\AsignacionResource\RelationManagers;
use App\Models\Asignacion;
use Doctrine\DBAL\Query;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Forms\Components;
use Filament\Tables\Columns\TextColumn;
use Icetalker\FilamentStepper\Forms\Components\Stepper;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class AsignacionResource extends Resource
{
    protected static ?string $model = Asignacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('cliente_id')
                ->label('Cliente')
                ->relationship('cliente', 'nombre'),
                Select::make('ingreso_id')
                ->label('Producto')
                ->relationship(
                    name: 'ingreso.producto',
                    titleAttribute: 'descripcion'),
                    //modifyQueryUsing: fn (Builder $query) => dd($query->where('id'))),
                TextInput::make('cantidad_asignada')->label('Cantidad')->integer(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('cliente.nombre')->label('Cliente'),
                TextColumn::make('ingreso.producto.descripcion')->label('Producto'),
                TextColumn::make('cantidad_asignada')->label('Cantidad'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAsignacions::route('/'),
            'create' => Pages\CreateAsignacion::route('/create'),
            'edit' => Pages\EditAsignacion::route('/{record}/edit'),
        ];
    }
}
