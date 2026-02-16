<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioFoto extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDFoto';

    protected $appends = ['url'];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio', 'IDServicio');
    }

    public function getUrlAttribute()
    {
        if (!$this->RutaFoto) return null;
        
        $ruta = $this->RutaFoto;
        // Fix for seeder paths
        if (strpos($ruta, 'images/') === 0) {
            $rutaTry = str_replace('images/', 'perfiles/', $ruta);
            if (file_exists(storage_path('app/public/' . ltrim($rutaTry, '/')))) {
                $ruta = $rutaTry;
            } else {
                $rutaTry = str_replace('images/', 'imagenes/', $ruta);
                if (file_exists(storage_path('app/public/' . ltrim($rutaTry, '/')))) {
                    $ruta = $rutaTry;
                }
            }
        }
        
        return asset('storage/' . ltrim($ruta, '/'));
    }
}
