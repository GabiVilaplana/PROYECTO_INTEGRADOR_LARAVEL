<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioFoto extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDFoto';
    protected $fillable = ['idServicio', 'RutaFoto', 'EsPrincipal'];
    protected $appends = ['url'];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio', 'IDServicio');
    }

    public function getUrlAttribute()
    {
        if (!$this->RutaFoto)
            return null;

        $ruta = $this->RutaFoto;
        
        // Fix for seeder paths (images/ -> perfiles/ or imagenes/)
        if (strpos($ruta, 'images/') === 0) {
            // First try perfiles/ (where seeders seemingly put them)
            $perfilesPath = str_replace('images/', 'perfiles/', $ruta);
            if (file_exists(storage_path('app/public/' . $perfilesPath))) {
                return asset('storage/' . $perfilesPath);
            }

            // Then try imagenes/
            $imagenesPath = str_replace('images/', 'imagenes/', $ruta);
            if (file_exists(storage_path('app/public/' . $imagenesPath))) {
                return asset('storage/' . $imagenesPath);
            }
            
            // If strictly 'images/', and we can't find it, we might want to return a default
            // But for now, let's assume it might be in perfiles and file_exists is failing for some reason,
            // or just return the original relative path so we see the 404 on the expected URL.
            // Actually, let's force perfiles if it's the specific seeder pattern
            if (strpos($ruta, '_principal.jpg') !== false) {
                 return asset('storage/' . $perfilesPath);
            }
        }

        return asset('storage/' . $ruta);
    }
}
