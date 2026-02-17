<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    // 1. EL NOMBRE DE TU TABLA REAL
    protected $table = 'usuarios';

    // 2. TU CLAVE PRIMARIA REAL
    protected $primaryKey = 'IDUsuario';

    // 3. CAMPOS QUE PERMITIMOS GUARDAR
    protected $fillable = [
        'Nombre',
        'CorreoElectronico',
        'Password',
        'idRol',
        'Activo',
    ];

    // 4. ESTO ES VITAL: Dile a Laravel que tu columna de clave es 'Password'
    public function getAuthPassword()
    {
        return $this->Password;
    }

    public function getEmailAttribute()
    {
        return $this->attributes['CorreoElectronico'];
    }

    // Desactivamos las etiquetas de tiempo si te dieran problemas, 
    // pero si las tienes en la DB, déjalo en true
    public $timestamps = true;
}