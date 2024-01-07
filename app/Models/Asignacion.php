<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
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
