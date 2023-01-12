<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


use App\Http\Controllers\Dist\GastosController;
use App\Http\Controllers\Dist\CompaniaController;
use App\Http\Controllers\Dist\ClienteController;
use App\Http\Controllers\Dist\UsuariosController;
use App\Http\Controllers\Dist\StoreCebececoController;
use App\Http\Controllers\Dist\ComentarioController;
use App\Http\Controllers\Dist\GeneralesController;
use App\Http\Controllers\Dist\SecundariaController;
use App\Http\Controllers\Dist\EspecificaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});*/


Route::get('/', [AuthenticatedSessionController::class, 'create'])
->name('login');


//Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');


  
    
Route::middleware('auth')->group(function () {

    

    //Generales
    Route::get('dist/generales', [GeneralesController::class, 'Index']) ->name('Index'); 
    Route::post('dist/generales', [GeneralesController::class, 'PostIndex']) ->name('PostIndex'); 
    Route::get('dist/generales/nuevo', [GeneralesController::class, 'Nuevo']) ->name('Nuevo'); 
    Route::post('dist/generales/nuevo', [GeneralesController::class, 'PostNuevo']) ->name('PostNuevo'); 
    Route::get('dist/generales/editar/{Id}', [GeneralesController::class, 'Editar']) ->name('Editar');
    Route::post('dist/generales/editar/{Id}', [GeneralesController::class, 'PostEditar']) ->name('PostEditar'); 
    Route::get('dist/generales/mostrar/{Id}', [GeneralesController::class, 'Mostrar']) ->name('Mostrar');
    Route::post('dist/generales/desactivar', [GeneralesController::class, 'Desactivar']) ->name('Desactivar');

    Route::get('dist/generales/importar', [GeneralesController::class, 'Importar']) ->name('Importar'); 
    Route::post('dist/generales/importar', [GeneralesController::class, 'PostImportar']) ->name('PostImportar'); 

    Route::post('dist/generales/listadoagregargenerales', [GeneralesController::class, 'Listadoagregargenerales']) ->name('Listadoagregargenerales'); 

    //Secundaria
    Route::get('dist/secundaria', [SecundariaController::class, 'Index']) ->name('Index'); 
    Route::post('dist/secundaria', [SecundariaController::class, 'PostIndex']) ->name('PostIndex'); 
    Route::get('dist/secundaria/nuevo', [SecundariaController::class, 'Nuevo']) ->name('Nuevo'); 
    Route::post('dist/secundaria/nuevo', [SecundariaController::class, 'PostNuevo']) ->name('PostNuevo'); 
    Route::get('dist/secundaria/editar/{Id}', [SecundariaController::class, 'Editar']) ->name('Editar');
    Route::post('dist/secundaria/editar/{Id}', [SecundariaController::class, 'PostEditar']) ->name('PostEditar'); 
    Route::get('dist/secundaria/mostrar/{Id}', [SecundariaController::class, 'Mostrar']) ->name('Mostrar');
    Route::post('dist/secundaria/desactivar', [SecundariaController::class, 'Desactivar']) ->name('Desactivar');

    Route::get('dist/secundaria/importar', [SecundariaController::class, 'Importar']) ->name('Importar'); 
    Route::post('dist/secundaria/importar', [SecundariaController::class, 'PostImportar']) ->name('PostImportar'); 

    Route::post('dist/secundaria/listadoagregarsecundaria', [SecundariaController::class, 'Listadoagregarsecundaria']) ->name('Listadoagregarsecundaria'); 

    //Especifica
    Route::get('dist/especifica', [EspecificaController::class, 'Index']) ->name('Index'); 
    Route::post('dist/especifica', [EspecificaController::class, 'PostIndex']) ->name('PostIndex'); 
    Route::get('dist/especifica/nuevo', [EspecificaController::class, 'Nuevo']) ->name('Nuevo'); 
    Route::post('dist/especifica/nuevo', [EspecificaController::class, 'PostNuevo']) ->name('PostNuevo'); 
    Route::get('dist/especifica/editar/{Id}', [EspecificaController::class, 'Editar']) ->name('Editar');
    Route::post('dist/especifica/editar/{Id}', [EspecificaController::class, 'PostEditar']) ->name('PostEditar'); 
    Route::get('dist/especifica/mostrar/{Id}', [EspecificaController::class, 'Mostrar']) ->name('Mostrar');
    Route::post('dist/especifica/desactivar', [EspecificaController::class, 'Desactivar']) ->name('Desactivar');

    Route::get('dist/especifica/importar', [EspecificaController::class, 'Importar']) ->name('Importar'); 
    Route::post('dist/especifica/importar', [EspecificaController::class, 'PostImportar']) ->name('PostImportar'); 


     //Gastos
     Route::get('dist/gastos', [GastosController::class, 'Index']) ->name('Index'); 
     Route::post('dist/gastos', [GastosController::class, 'PostIndex']) ->name('PostIndex'); 
     Route::get('dist/gastos/nuevo', [GastosController::class, 'Nuevo']) ->name('Nuevo'); 
     Route::post('dist/gastos/nuevo', [GastosController::class, 'PostNuevo']) ->name('PostNuevo'); 
     Route::get('dist/gastos/editar/{Id}', [GastosController::class, 'Editar']) ->name('Editar');
     Route::post('dist/gastos/editar/{Id}', [GastosController::class, 'PostEditar']) ->name('PostEditar'); 
     Route::get('dist/gastos/mostrar/{Id}', [GastosController::class, 'Mostrar']) ->name('Mostrar');
     Route::post('dist/gastos/desactivar', [GastosController::class, 'Desactivar']) ->name('Desactivar');
    
     //Gastos Cuenta
     Route::get('dist/gastoscuenta', [GastosController::class, 'gastosCuentaIndex']) ->name('gastosCuentaIndex');
     Route::post('dist/gastoscuenta', [GastosController::class, 'gastosCuentaPostIndex']) ->name('gastosCuentaPostIndex'); 

     Route::get('dist/gastos/importar', [GastosController::class, 'Importar']) ->name('Importar'); 
     Route::post('dist/gastos/importar', [GastosController::class, 'PostImportar']) ->name('PostImportar'); 
 
    

     //Compania
    Route::get('dist/compania', [CompaniaController::class, 'Index']) ->name('Index'); 
    Route::post('dist/compania', [CompaniaController::class, 'PostIndex']) ->name('PostIndex'); 
    Route::get('dist/compania/nuevo', [CompaniaController::class, 'Nuevo']) ->name('Nuevo'); 
    Route::post('dist/compania/nuevo', [CompaniaController::class, 'PostNuevo']) ->name('PostNuevo'); 
    Route::get('dist/compania/editar/{Id}', [CompaniaController::class, 'Editar']) ->name('Editar');
    Route::post('dist/compania/editar/{Id}', [CompaniaController::class, 'PostEditar']) ->name('PostEditar'); 
    Route::get('dist/compania/mostrar/{Id}', [CompaniaController::class, 'Mostrar']) ->name('Mostrar');
    Route::post('dist/compania/desactivar', [CompaniaController::class, 'Desactivar']) ->name('Desactivar');
  
    //StoreCebececo
    Route::get('dist/storecebececo', [StoreCebececoController::class, 'Index']) ->name('Index'); 
    Route::post('dist/storecebececo', [StoreCebececoController::class, 'PostIndex']) ->name('PostIndex'); 
    Route::get('dist/storecebececo/nuevo', [StoreCebececoController::class, 'Nuevo']) ->name('Nuevo'); 
    Route::post('dist/storecebececo/nuevo', [StoreCebececoController::class, 'PostNuevo']) ->name('PostNuevo'); 
    Route::get('dist/storecebececo/editar/{Id}', [StoreCebececoController::class, 'Editar']) ->name('Editar');
    Route::post('dist/storecebececo/editar/{Id}', [StoreCebececoController::class, 'PostEditar']) ->name('PostEditar'); 
    Route::get('dist/storecebececo/mostrar/{Id}', [StoreCebececoController::class, 'Mostrar']) ->name('Mostrar');
    Route::post('dist/storecebececo/desactivar', [StoreCebececoController::class, 'Desactivar']) ->name('Desactivar');

    Route::get('dist/storecebececo/importar', [StoreCebececoController::class, 'Importar']) ->name('Importar'); 
    Route::post('dist/storecebececo/importar', [StoreCebececoController::class, 'PostImportar']) ->name('PostImportar'); 

    //Comentario
    Route::get('dist/comentario', [ComentarioController::class, 'Index']) ->name('Index'); 
    Route::post('dist/comentario', [ComentarioController::class, 'PostIndex']) ->name('PostIndex'); 
    Route::get('dist/comentario/nuevo', [ComentarioController::class, 'Nuevo']) ->name('Nuevo'); 
    Route::post('dist/comentario/nuevo', [ComentarioController::class, 'PostNuevo']) ->name('PostNuevo'); 
    Route::get('dist/comentario/editar/{Id}', [ComentarioController::class, 'Editar']) ->name('Editar');
    Route::post('dist/comentario/editar/{Id}', [ComentarioController::class, 'PostEditar']) ->name('PostEditar'); 
    Route::get('dist/comentario/mostrar/{Id}', [ComentarioController::class, 'Mostrar']) ->name('Mostrar');
    Route::post('dist/comentario/desactivar', [ComentarioController::class, 'Desactivar']) ->name('Desactivar');


    //Cliente
  Route::get('dist/cliente', [ClienteController::class, 'Index']) ->name('Index'); 
    
    // Usuarios
    Route::get('dist/usuarios', [UsuariosController::class, 'Index']) ->name('Index'); 

});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
