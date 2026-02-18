<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDServicio';
    protected $appends = ['imagen_url', 'promedio_valoracion'];

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Precio',
        'Duracion',
        'Activo',
        'idCategoria',
        'idProveedor',
        'lat',
        'lng',
        'radio_km',
    ];

    public function getPromedioValoracionAttribute()
    {
        $promedio = $this->valoraciones()->avg('Puntuacion') ?: 0;
        return round($promedio, 1);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria', 'IDCategoria');
    }

    public function proveedor()
    {
        return $this->belongsTo(Usuario::class, 'idProveedor', 'IDUsuario');
    }

    public function fotos()
    {
        return $this->hasMany(ServicioFoto::class, 'idServicio', 'IDServicio');
    }

    public function fotoPrincipal()
    {
        return $this->hasOne(ServicioFoto::class, 'idServicio', 'IDServicio')->where('EsPrincipal', 1);
    }

    public function valoraciones()
    {
        return $this->hasMany(ValoracionServicio::class, 'idServicio', 'IDServicio');
    }

    public function reservaDetalles()
    {
        return $this->hasMany(ReservaDetalle::class, 'idServicio', 'IDServicio');
    }
    public function getRouteKeyName()
    {
        return 'IDServicio';
    }
    public function getImagenUrlAttribute()
    {
        // 1. Intentar obtener la foto principal del servicio
        $foto = $this->relationLoaded('fotoPrincipal') ? $this->fotoPrincipal : $this->fotoPrincipal()->first();

        if ($foto) {
            return $foto->url;
        }

        // 2. Si no hay foto principal, intentar usar la imagen de la categoría
        $categoria = $this->relationLoaded('categoria') ? $this->categoria : $this->categoria()->first();

        if ($categoria && $categoria->Imagen) {
            return asset('storage/' . ltrim(strtolower($categoria->Imagen), '/'));
        }

        // 3. Imagen por defecto final
        return asset('storage/perfiles/default.jpg');
    }
    public function disponibilidades()
    {
        return $this->hasMany(
            ServicioDisponibilidad::class,
            'idServicio',      
            'IDServicio'       
        );
    }

    public function getAvailableSlots($fecha)
    {
        $fechaObj = \Carbon\Carbon::parse($fecha);
        $diaSemana = $fechaObj->dayOfWeek; // 0 (Domingo) a 6 (Sábado)

        // 1. Obtener disponibilidad del proveedor para ese día
        $disponibilidad = $this->disponibilidades()
            ->where('dia_semana', $diaSemana)
            ->where('activo', true)
            ->get();

        if ($disponibilidad->isEmpty()) {
            return [];
        }

        // 2. Obtener reservas existentes para ese día
        $reservas = ReservaDetalle::where('idServicio', $this->IDServicio)
            ->where('FechaServicio', $fecha)
            ->orderBy('HoraServicio')
            ->get();

        $slots = [];
        $buffer = 20; // 20 minutos de descanso
        $duracionTotal = $this->Duracion + $buffer;

        foreach ($disponibilidad as $disp) {
            $inicio = \Carbon\Carbon::parse($fecha . ' ' . $disp->hora_inicio);
            $fin = \Carbon\Carbon::parse($fecha . ' ' . $disp->hora_fin);

            $currentSlot = $inicio->copy();

            while ($currentSlot->copy()->addMinutes($this->Duracion)->lte($fin)) {
                $slotStart = $currentSlot->toTimeString();
                $slotEnd = $currentSlot->copy()->addMinutes($this->Duracion)->toTimeString();
                $slotFullEnd = $currentSlot->copy()->addMinutes($duracionTotal)->toTimeString();

                // Verificar si el slot choca con alguna reserva
                $isOccupied = $reservas->contains(function ($reserva) use ($slotStart, $slotFullEnd) {
                    $resStart = $reserva->HoraServicio;
                    $resEnd = \Carbon\Carbon::parse($resStart)->addMinutes($this->Duracion + 20)->toTimeString();

                    return ($slotStart >= $resStart && $slotStart < $resEnd) ||
                        ($slotFullEnd > $resStart && $slotFullEnd <= $resEnd);
                });

                if (!$isOccupied) {
                    $slots[] = [
                        'inicio' => $slotStart,
                        'fin' => $slotEnd
                    ];
                }

                $currentSlot->addMinutes($duracionTotal);
            }
        }

        return $slots;
    }
}