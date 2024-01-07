<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $fillable = [
        'producto_id', // Asegúrate de agregar todos los campos necesarios
        'cantidad',
        'observacion',
        // Otros campos
    ];
    
    public function asignaciones()
{
    return $this->hasMany(Asignacion::class);
}
    use HasFactory;

    public function producto()
{
    return $this->belongsTo(Producto::class);
}
}

