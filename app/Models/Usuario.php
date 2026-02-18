<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory;


    // 2. TU CLAVE PRIMARIA REAL
    protected $primaryKey = 'IDUsuario';

    // 3. CAMPOS QUE PERMITIMOS GUARDAR
    protected $fillable = [
        'Nombre',
        'Apellidos',
        'email',
        'password',  // ¡esta es la columna real!

        'idRol',
        'Activo',
        'FotoPerfil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
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
        return $this->Nombre . ' ' . $this->Apellidos;
    }

    // URL foto perfil
    public function getFotoPerfilUrlAttribute()
    {
        if ($this->FotoPerfil) {
            return asset('storage/' . ltrim($this->FotoPerfil, '/'));
        }
        return asset('storage/perfiles/default.jpg');
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    public function getAuthIdentifierName()
    {
        return 'IDUsuario';
    }
}
