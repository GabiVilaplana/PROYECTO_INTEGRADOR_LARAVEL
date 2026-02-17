<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServicioDisponibilidad;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioDisponibilidadApiController extends Controller
{
    public function index($servicioId)
    {
        $disponibilidades = ServicioDisponibilidad::where('idServicio', $servicioId)
            ->orderBy('dia_semana')
            ->get();

        return response()->json($disponibilidades);
    }

    public function update(Request $request, $servicioId)
    {
        $request->validate([
            'disponibilidad' => 'required|array',
            'disponibilidad.*.dia_semana' => 'required|integer|min:0|max:6',
            'disponibilidad.*.hora_inicio' => 'required|date_format:H:i',
            'disponibilidad.*.hora_fin' => 'required|date_format:H:i',
            'disponibilidad.*.activo' => 'required|boolean',
        ]);

        $usuario = $request->user();

        // Verificar que el servicio pertenece al usuario
        $servicio = Servicio::where('IDServicio', $servicioId)
            ->where('idProveedor', $usuario->IDUsuario)
            ->firstOrFail();

        // Eliminar disponibilidades existentes
        ServicioDisponibilidad::where('idServicio', $servicioId)->delete();

        // Crear nuevas disponibilidades
        $disponibilidades = [];
        foreach ($request->disponibilidad as $disp) {
            $disponibilidades[] = ServicioDisponibilidad::create([
                'idServicio' => $servicioId,
                'dia_semana' => $disp['dia_semana'],
                'hora_inicio' => $disp['hora_inicio'],
                'hora_fin' => $disp['hora_fin'],
                'activo' => $disp['activo'],
            ]);
        }

        return response()->json($disponibilidades);
    }
}
