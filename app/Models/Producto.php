<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo',
        // Otros campos del modelo
        'descripcion',
        'tipo_producto',
        'tipo_medida',
    ];

    use HasFactory;

    public function ingresos()
    {
        return $this->hasMany(Ingreso::class);
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'ingreso_id');
    }



}

