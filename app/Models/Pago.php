<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDPago';

    protected $fillable = [
        'idReserva',
        'MetodoPago',
        'Estado',
        'Importe',
        'FechaPago',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'idReserva', 'IDReserva');
    }
}
