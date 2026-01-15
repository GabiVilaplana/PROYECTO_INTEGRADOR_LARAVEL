<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValoracionServicio extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDValoracion';

    protected $fillable = [
        'idServicio',
        'idUsuario',
        'Puntuacion',
        'Comentario',
        'Fecha',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio', 'IDServicio');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'IDUsuario');
    }
}
