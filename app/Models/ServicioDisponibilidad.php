<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioDisponibilidad extends Model
{
    use HasFactory;

    protected $table = 'servicio_disponibilidades';
    protected $primaryKey = 'IDDisponibilidad';

    protected $fillable = [
        'idServicio',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'activo',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio', 'IDServicio');
    }
}
