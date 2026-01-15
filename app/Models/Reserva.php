<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDReserva';

    protected $fillable = [
        'idUsuario',
        'FechaReserva',
        'Estado',
        'Total',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'IDUsuario');
    }

    public function detalles()
    {
        return $this->hasMany(ReservaDetalle::class, 'idReserva', 'IDReserva');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'idReserva', 'IDReserva');
    }
}
