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
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Models\Ingreso;
use App\Models\Producto;
use Closure;
use Filament\Forms\Get;

class AsignacionResource extends Resource
{
    protected static ?string $model = Asignacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('cliente_id')
                ->label('Cliente')
                ->required()
                ->relationship('cliente', 'nombre'),
                Select::make('ingreso_id')
                ->label('Producto')
                ->required()
                ->relationship(
                    name: 'ingreso.producto',
                    titleAttribute: 'descripcion'),
                    //modifyQueryUsing: fn (Builder $query) => dd($query->where('id'))),
                TextInput::make('cantidad_asignada')
                ->label('Cantidad')
                ->integer()
                ->required()
                ->rules([
                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                        $producto = Producto::find($get('ingreso_id'));
                        $cantidad_producto = $producto->ingresos->sum('cantidad');
                        $asignada = $producto->asignaciones->sum('cantidad_asignada');
                        $total  = $cantidad_producto - ($asignada + $value);
                        $diferencia =  $cantidad_producto - $asignada;
                        if ($total < 0) {
                            $fail("No puede ingresar una cantidad mayor a {$diferencia}");
                        }
                    },
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('cliente.cedula')->label('Cedula'),
                TextColumn::make('cliente.nombre')->label('Cliente'),
                TextColumn::make('ingreso.producto.descripcion')->label('Producto entregado'),
                TextColumn::make('cantidad_asignada')->label('Cantidad'),
                TextColumn::make('created_at')->label('Asignado'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
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
