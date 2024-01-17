<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngresoResource\Pages;
use App\Filament\Resources\IngresoResource\RelationManagers;
use App\Models\Ingreso;
use Faker\Core\Number;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Forms\Components;
use Filament\Tables\Columns\TextColumn;
use Icetalker\FilamentStepper\Forms\Components\Stepper;
use Illuminate\Database\Eloquent\Model;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class IngresoResource extends Resource
{
    protected static ?string $model = Ingreso::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Select::make('producto_id')
                ->label('Producto')
                ->relationship('producto', 'descripcion'),
            TextInput::make('cantidad')->label('Cantidad')->integer(),
            Textarea::make('observacion')->label('Observación') ,
            
            
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('producto.codigo')->label('Codigo'),
                TextColumn::make('producto.descripcion')->label('Producto'),
                TextColumn::make('cantidad')->getStateUsing(function(Model $record) {
                    $record->load('asignaciones');
                    $cantidadDisponible = $record->cantidad - $record->asignaciones->sum('cantidad_asignada');
                    
                    // return whatever you need to show
                    //dd($record->asignaciones->sum('cantidad_asignada'));
                    return $record->cantidad - $record->asignaciones->sum('cantidad_asignada');
                    
                }),
                TextColumn::make('observacion'),
                TextColumn::make('created_at')->label('Ingresados'),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make(),
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
            'index' => Pages\ListIngresos::route('/'),
            'create' => Pages\CreateIngreso::route('/create'),
            'edit' => Pages\EditIngreso::route('/{record}/edit'),
        ];
    }
}
