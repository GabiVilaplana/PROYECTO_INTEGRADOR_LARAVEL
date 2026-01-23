<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDCategoria';

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Color',
        'Activa',
        'Imagen',
    ];

    // Relación con servicios
    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'idCategoria', 'IDCategoria');
    }
    public function getImagenAttribute($value)
    {
        return $value ?? 'categorias/default.jpg';
    }
}
