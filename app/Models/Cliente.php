<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'cedula',
        // Otros campos del modelo
        'nombre',

    ];
    public function asignaciones()
{
    return $this->hasMany(Asignacion::class);
}
    use HasFactory;
}
