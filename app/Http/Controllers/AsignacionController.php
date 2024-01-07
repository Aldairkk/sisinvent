<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asignacion;
use App\Models\Ingreso;
use App\Models\Cliente;

class AsignacionController extends Controller
{
    //
    public function asignar(Request $request)
    {
        // Validar el formulario, por ejemplo
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'ingreso_id' => 'required|exists:ingresos,id',
            'cantidad_asignada' => 'required|numeric|min:0',
        ]);

        // Crear la asignación
        $asignacion = Asignacion::create([
            'cliente_id' => $request->input('cliente_id'),
            'ingreso_id' => $request->input('ingreso_id'),
            'cantidad_asignada' => $request->input('cantidad_asignada'),
        ]);

        // Actualizar la cantidad disponible en el ingreso
        $ingreso = Ingreso::find($request->input('ingreso_id'));
        $ingreso->cantidad_disponible -= $request->input('cantidad_asignada');
        $ingreso->save();

        // Sumar la cantidad asignada al cliente
        $cliente = Cliente::find($request->input('cliente_id'));
        $cliente->cantidad_asignada += $request->input('cantidad_asignada');
        $cliente->save();

        // Puedes redirigir a una página o devolver una respuesta JSON según tus necesidades
        return redirect()->route('asignacions'); // Ajusta la ruta según tu aplicación
    }
}
