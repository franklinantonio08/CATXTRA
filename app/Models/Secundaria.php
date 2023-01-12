<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secundaria extends Model
{
    use HasFactory;

    protected $table = 'bo_kpi_secundaria';


    /*protected $fillable = [
        'regional',
        'segmento',
        'formato',
        'nombre_segmento',
        'direccion',
        'cebe_ceco',
    ];*/
}
