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
    ];

    // Relación con categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria', 'IDCategoria');
    }

    // Relación con proveedor (usuario)
    public function proveedor()
    {
        return $this->belongsTo(Usuario::class, 'idProveedor', 'IDUsuario');
    }

    // Fotos del servicio
    public function fotos()
    {
        return $this->hasMany(ServicioFoto::class, 'idServicio', 'IDServicio');
    }

    // Valoraciones
    public function valoraciones()
    {
        return $this->hasMany(ValoracionServicio::class, 'idServicio', 'IDServicio');
    }

    // Reservas
    public function reservaDetalles()
    {
        return $this->hasMany(ReservaDetalle::class, 'idServicio', 'IDServicio');
    }
}
