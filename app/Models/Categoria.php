<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDCategoria';
    protected $appends = ['imagen_url'];

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
    public function getImagenUrlAttribute()
    {
        if (!$this->Imagen) {
            return asset('storage/categorias/default.jpg');
        }
        
        if (filter_var($this->Imagen, FILTER_VALIDATE_URL)) {
            return $this->Imagen;
        }

        return asset('storage/' . ltrim($this->Imagen, '/'));
    }
}
