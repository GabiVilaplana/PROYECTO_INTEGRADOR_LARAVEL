<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $primaryKey = 'IDFaq';

    protected $fillable = [
        'idFaqCategoria',
        'Pregunta',
        'Respuesta',
    ];

    public function categoria()
    {
        return $this->belongsTo(FaqCategory::class, 'idFaqCategoria', 'IDFaqCategoria');
    }
}
