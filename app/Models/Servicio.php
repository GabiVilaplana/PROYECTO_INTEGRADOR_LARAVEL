<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDServicio';

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
    // app/Models/Servicio.php
}