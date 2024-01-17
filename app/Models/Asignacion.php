<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{

    protected static function booted()
    {
        static::saving(function ($asignacion) {
            $ingreso = $asignacion->ingreso;

            // Verificar si hay suficiente stock en el ingreso actual
            if ($ingreso->cantidad < $asignacion->cantidad_asignada) {
                // Buscar el siguiente ingreso con stock disponible
                $siguienteIngreso = Ingreso::where('producto_id', $ingreso->producto_id)
                    ->where('cantidad', '>', 0)
                    ->orderBy('created_at')
                    ->first();

                if ($siguienteIngreso) {
                    // Restar la cantidad asignada del siguiente ingreso
                    $asignacion->ingreso_id = $siguienteIngreso->id;
                    $asignacion->save();
                } else {
                    // No hay más ingresos disponibles, puedes manejar esto según tus necesidades
                    // Por ejemplo, puedes lanzar una excepción, enviar una notificación, etc.
                    throw new \Exception('No hay más ingresos disponibles para este producto.');
                }
            }
        });
    }

    protected $fillable = [
        'cliente_id',
        // Otros campos del modelo
        'ingreso_id',
        'cantidad_asignada',

    ];


public function cliente()
{
    return $this->belongsTo(Cliente::class);
}

public function ingreso()
{
    return $this->belongsTo(Ingreso::class);
}
public function producto()
{
    return $this->belongsTo(Producto::class);
}

    use HasFactory;
}
