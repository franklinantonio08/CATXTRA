<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreCebececo extends Model
{
    use HasFactory;


    protected $table = 'bo_kpi_store_cebececo';


    protected $fillable = [
        'regional',
        'segmento',
        'formato',
        'nombre_segmento',
        'direccion',
        'cebe_ceco',
    ];


}
