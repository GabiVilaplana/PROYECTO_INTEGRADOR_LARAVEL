<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'IDUsuario';

    protected $fillable = [
        'Nombre',
        'Apellidos',
        'CorreoElectronico',
        'Password',
        'idRol',
        'Activo',
    ];

    // Relación con rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idRol', 'IDRol');
    }

    // Servicios creados por el usuario (si es proveedor)
    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'idProveedor', 'IDUsuario');
    }

    // Reservas hechas por el usuario
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'idUsuario', 'IDUsuario');
    }

    // Valoraciones realizadas
    public function valoraciones()
    {
        return $this->hasMany(ValoracionServicio::class, 'idUsuario', 'IDUsuario');
    }

    // Mensajes enviados
    public function mensajes()
    {
        return $this->hasMany(Mensaje::class, 'idUsuario', 'IDUsuario');
    }

    public function getEmailAttribute()
    {
        return $this->attributes['CorreoElectronico'];
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['Password'] = bcrypt($value);
    }

    public function getAuthPassword()
    {
        return $this->attributes['Password'];
    }
}
