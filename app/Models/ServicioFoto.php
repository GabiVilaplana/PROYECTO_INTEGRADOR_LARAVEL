<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioFoto extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDFoto';

    protected $fillable = [
        'idServicio',
        'RutaFoto',
        'EsPrincipal',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio', 'IDServicio');
    }
}
