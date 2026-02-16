<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDServicio';
    protected $appends = ['imagen_url', 'promedio_valoracion'];

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

    public function getPromedioValoracionAttribute()
    {
        $promedio = $this->valoraciones()->avg('Puntuacion') ?: 0;
        return round($promedio, 1);
    }

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
    public function getImagenUrlAttribute()
    {
        // 1. Intentar obtener la foto principal del servicio
        $foto = $this->relationLoaded('fotoPrincipal') ? $this->fotoPrincipal : $this->fotoPrincipal()->first();

        if ($foto && $foto->RutaFoto) {
            return asset('storage/' . ltrim($foto->RutaFoto, '/'));
        }

        // 2. Si no hay foto principal, intentar usar la imagen de la categoría
        $categoria = $this->relationLoaded('categoria') ? $this->categoria : $this->categoria()->first();

        if ($categoria && $categoria->Imagen) {
            return asset('storage/' . ltrim(strtolower($categoria->Imagen), '/'));
        }

        // 3. Imagen por defecto final
        return asset('storage/perfiles/default.jpg');
    }
}