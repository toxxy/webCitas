<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citas2 extends Model
{
    use HasFactory;

    protected $table = 'citas2s';
    protected $fillable = [
        'orden',
        'descripcion',
        'status',
        'FechaProgramada',
        'FechaRealizada',
        'RFC',
        'Nombre'
    ];
}
