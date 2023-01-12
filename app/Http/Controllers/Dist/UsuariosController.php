<?php

namespace App\Http\Controllers\dist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    //

    public function index(){

        return \View::make('dist/usuarios/index');

    }
    
}
