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
        'password',  // ¡esta es la columna real!
        'idRol',
        'Activo',
        'FotoPerfil',
    ];

    // Relaciones
    public function rol() { return $this->belongsTo(Rol::class, 'idRol', 'IDRol'); }
    public function servicios() { return $this->hasMany(Servicio::class, 'idProveedor', 'IDUsuario'); }
    public function reservas() { return $this->hasMany(Reserva::class, 'idUsuario', 'IDUsuario'); }
    public function valoraciones() { return $this->hasMany(ValoracionServicio::class, 'idUsuario', 'IDUsuario'); }
    public function mensajes() { return $this->hasMany(Mensaje::class, 'idUsuario', 'IDUsuario'); }

    // Sobrescribir email
    public function getEmailAttribute()
    {
        return $this->attributes['CorreoElectronico'];
    }

    // Sobrescribir password para hacer bcrypt automáticamente
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // Laravel usa este método para verificar la contraseña
    public function getAuthPassword(): string
    {
        return $this->attributes['password'];
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
        return asset('IMG/imagenPerfilRedonda.png');
    }

    // Login con CorreoElectronico
    public function getAuthIdentifierName(): string
    {
        return 'CorreoElectronico';
    }
}
