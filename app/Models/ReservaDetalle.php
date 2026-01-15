<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservaDetalle extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDDetalle';

    protected $fillable = [
        'idReserva',
        'idServicio',
        'Precio',
        'FechaServicio',
        'HoraServicio',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'idReserva', 'IDReserva');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio', 'IDServicio');
    }
}
