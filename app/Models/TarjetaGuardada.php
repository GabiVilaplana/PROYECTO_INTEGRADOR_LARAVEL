<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarjetaGuardada extends Model
{
    use HasFactory;

    protected $table = 'tarjetas_guardadas';
    protected $primaryKey = 'IDTarjeta';

    protected $fillable = [
        'idUsuario',
        'NombreTitular',
        'NumeroTarjeta',
        'MesExpiracion',
        'AnioExpiracion',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'IDUsuario');
    }
}
