<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $appends = ['foto_perfil_url'];


    // 2. TU CLAVE PRIMARIA REAL
    protected $primaryKey = 'IDUsuario';

    // 3. CAMPOS QUE PERMITIMOS GUARDAR
    protected $fillable = [
        'Nombre',
        'Apellidos',
        'name', // Virtual field for fill()
        'email',
        'password',
        'google_id',
        'google_token',
        'google_refresh_token',
        'idRol',
        'Activo',
        'FotoPerfil',
        'email_verified_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relaciones
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idRol', 'IDRol');
    }
    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'idProveedor', 'IDUsuario');
    }
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'idUsuario', 'IDUsuario');
    }
    public function valoraciones()
    {
        return $this->hasMany(ValoracionServicio::class, 'idUsuario', 'IDUsuario');
    }
    public function mensajes()
    {
        return $this->hasMany(Mensaje::class, 'idUsuario', 'IDUsuario');
    }

    // Nombre completo
    public function getNameAttribute()
    {
        return trim($this->Nombre . ' ' . $this->Apellidos);
    }

    public function setNameAttribute($value)
    {
        if (empty($value))
            return;

        $partes = explode(' ', $value, 2);
        $this->attributes['Nombre'] = $partes[0];
        $this->attributes['Apellidos'] = $partes[1] ?? '';
    }

    // URL foto perfil
    public function getFotoPerfilUrlAttribute()
    {
        if ($this->FotoPerfil) {
            return asset('storage/' . ltrim($this->FotoPerfil, '/'));
        }
        return asset('storage/perfiles/default.jpg');
    }
    public function getAuthIdentifierName()
    {
        return 'IDUsuario';
    }

    /**
     * Get the value of the model's primary key for compatibility with Laravel internals (like 'id').
     */
    public function getIdAttribute()
    {
        return $this->attributes['IDUsuario'] ?? null;
    }
}
