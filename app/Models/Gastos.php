<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gastos extends Model
{
    use HasFactory;

    protected $table = 'bi_kpi_gastos';

    protected $fillable = [
        'nombre',
        //'segmento',
        //'formato',
        //'nombre_segmento',
        //'direccion',
        //'cebe_ceco',
    ];

}
