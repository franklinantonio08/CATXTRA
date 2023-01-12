<?php

namespace App\Http\Controllers\dist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    //

    public function index(){

        return \View::make('dist/cliente/index');

    }
    
}
