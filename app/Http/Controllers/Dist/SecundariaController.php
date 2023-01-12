<?php

namespace App\Http\Controllers\Dist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Imports\SecundariaImport;

use App\Models\Secundaria;

use DB;
use Excel;

class SecundariaController extends Controller
{
    
    private $request;
	private $common;

    public function __construct(Request $request){
        $this->request = $request;
        //$this->common = new Common;
    }

    public function Index(){

        return \View::make('dist/secundaria/index');

    }

    public function PostIndex(){
    			
		$request = $this->request->all();
		$columnsOrder = isset($request['order'][0]['column']) ? $request['order'][0]['column'] : '0';
		$orderBy=isset($request['columns'][$columnsOrder]['data']) ? $request['columns'][$columnsOrder]['data'] : 'id';
		$order = isset($request['order'][0]['dir']) ? $request['order'][0]['dir'] : 'ASC';
		$length = isset($request['length']) ? $request['length'] : '15';

		$currentPage = $request['currentPage'];  
		Paginator::currentPageResolver(function() use ($currentPage){
			return $currentPage;
		});

		$query = DB::table('bo_kpi_secundaria')
		->leftjoin('bo_kpi_general', 'bo_kpi_general.codeGeneral', '=', 'bo_kpi_secundaria.codeGeneral')
		->select('bo_kpi_general.id as idGeneral', 
			'bo_kpi_general.nombre as nombreGeneral',
			'bo_kpi_secundaria.*'
		)
         ->orderBy($orderBy,$order);
		

		/*if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where('bo_kpi_secundaria.nombre_segmento', 'like', '%'.trim($request['searchInput']).'%');
		}*/

        if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where(
				function ($query) use ($request) {
					$query->orWhere('bo_kpi_general.nombre', 'like', '%'.trim($request['searchInput']).'%');
					$query->orWhere('bo_kpi_secundaria.codeGeneral', 'like', '%'.trim($request['searchInput']).'%');
					$query->orWhere('bo_kpi_secundaria.nombre', 'like', '%'.trim($request['searchInput']).'%');
                    $query->orWhere('bo_kpi_secundaria.codeSecundaria', 'like', '%'.trim($request['searchInput']).'%');

				}
			 );		
		}
		   
		$secundaria = $query->paginate($length); 
	
		$result = $secundaria->toArray();
		$data = array();
		foreach($result['data'] as $value){

			if($value->estatus == 'Activo'){
				$detalle = '<a href="/dist/secundaria/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/secundaria/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-danger text-white m-b-5 desactivar"> <i class="bi bi-trash"></i> </a>';
			}else{
				$detalle = '<a href="/dist/secundaria/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/secundaria/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-primary text-white m-b-5 desactivar"> <i class="bi bi-check2-square"></i> </a>';
			}

			$data[] = array(
				  "DT_RowId" => $value->id,
				  "id" => $value->id,
				  "nombreGeneral" => $value->nombreGeneral,
				  "nombre"=> $value->nombre,
                  "codeSecundaria"=> $value->codeSecundaria,
				  "estatus"=> $value->estatus,
				  "detalle"=> $detalle
			);
		}

		$response = array(
				'draw' => isset($request['draw']) ? $request['draw'] : '1',
				'recordsTotal' => $result['total'],
				'recordsFiltered' => $result['total'],
				'data' => $data,
			);
		return response()
              ->json($response);
    }

    public function Nuevo(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/
    	
    	return \View::make('dist/secundaria/nuevo');
    }

    public function postNuevo(){
    	
        /*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

        //return $this->request->all();

    	$secundariaExiste = Secundaria::where('nombre', $this->request->nombre)
        ->where('codeGeneral', $this->request->generalesCodigo)
        ->first();
    	if(!empty($secundariaExiste)){
    		return redirect('dist/secundaria/nuevo')->withErrors("ERROR AL GUARDAR EL SECUNDARIA CODE-0001");
    	}

		DB::beginTransaction();
		try { 	
			$secundaria = new Secundaria;
			$secundaria->nombre         = trim($this->request->nombre);
			$secundaria->codeGeneral    = trim($this->request->generalesCodigo);
            

			//if(isset($this->request->codDireccion)){
			//$secundaria->cod_direccion    = trim($this->request->codDireccion);
			//}

			if(isset($this->request->comentario)){
				$secundaria->comentario       = trim($this->request->comentario); 
			}
			
			$secundaria->estatus          = 'Activo';
			$secundaria->created_at       = date('Y-m-d H:i:s');
			$secundaria->usuarioId        = Auth::user()->id;
			$result = $secundaria->save();

            $secundariaId = $secundaria->id;

            $codeSecundaria = str_pad($secundariaId, 4, "0",STR_PAD_LEFT);

			$secundariaUpdate = Secundaria::find($secundariaId);
			$secundariaUpdate->codeSecundaria = $codeSecundaria;
			$result = $secundariaUpdate->save();		


		} catch(\Illuminate\Database\QueryException $ex){ 
			DB::rollBack();
			return redirect('dist/secundaria/nuevo')->withErrors('ERROR AL GUARDAR EL SECUNDARIA CODE-0002'.$ex);
		}
		
		if($result != 1){
			DB::rollBack();
			return redirect('dist/secundaria/nuevo')->withErrors("ERROR AL GUARDAR EL SECUNDARIA CODE-0003");
		}
		DB::commit();

		return redirect('dist/secundaria')->with('alertSuccess', 'EL SECUNDARIA HA SIDO INGRESADA');
    }

    public function Editar($secundariaId){
		/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

		$secundaria = DB::table('bo_kpi_secundaria')
		->where('bo_kpi_secundaria.id', '=', $secundariaId)
		//->select('bo_kpi_secundaria.*')
		//->first();
		->leftjoin('bo_kpi_general', 'bo_kpi_general.codeGeneral', '=', 'bo_kpi_secundaria.codeGeneral')
		->select('bo_kpi_general.id as idGeneral', 
		'bo_kpi_general.nombre as nombreGenerales',
		'bo_kpi_secundaria.*'
		)
		->first();

/*
    	$secundaria = DB::table('bo_kpi_secundaria')
		 ->where('bo_kpi_secundaria.id', '=', $secundariaId)
		 //->where('rubro.distribuidorId', Auth::user()->distribuidorId)
		 ->leftjoin('bo_kpi_general', 'bo_kpi_general.codeGeneral', '=', 'bo_kpi_secundaria.codeGeneral')
		 ->select('bo_kpi_secundaria.*')->first(); */

    	if(empty($secundaria)){
    		return redirect('dist/secundaria')->withErrors("ERROR EL SECUNDARIA NO EXISTE CODE-0004");
    	}

    	view()->share('secundaria', $secundaria);
    	return \View::make('dist/secundaria/editar');
    }

    public function PostEditar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$request = $this->request->all();

        //return $request;

    	$secundariaId = isset($this->request->secundariaId) ? $this->request->secundariaId: '';

        //return $companiaId;

    	$secundaria = Secundaria::where('id', $secundariaId)
        //->where('distribuidorId',Auth::user()->distribuidorId)
        ->first();

    	if(empty($secundaria)){
    		return redirect('dist/secundaria')->withErrors("ERROR EL SECUNDARIA NO EXISTE CODE-0005");
    	}

		DB::beginTransaction();
	    	$secundariaUpdate = Secundaria::find($secundariaId);
			$secundariaUpdate->nombre          = $this->request->nombre;
            $secundariaUpdate->comentario      = $this->request->comentario;
            $result = $secundariaUpdate->save();

		if($result != 1){
			DB::rollBack();

			return redirect('dist/secundaria/editar/'.$secundariaId)->withErrors("ERROR AL EDITAR ELEMENTOS DE EL SECUNDARIA CODE-0006");
		}

		DB::commit();

		return redirect('dist/secundaria/')->with('alertSuccess', 'EL SECUNDARIA HA SIDO EDITADO');
    }

    public function Mostrar($secundariaId){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$secundaria = DB::table('bo_kpi_secundaria')
		->where('bo_kpi_secundaria.id', '=', $secundariaId)
		//->select('bo_kpi_secundaria.*')
		//->first();
		->leftjoin('bo_kpi_general', 'bo_kpi_general.codeGeneral', '=', 'bo_kpi_secundaria.codeGeneral')
		->select('bo_kpi_general.id as idGeneral', 
		'bo_kpi_general.nombre as nombreGenerales',
		'bo_kpi_secundaria.*'
		)
		->first();

    	if(empty($secundaria)){
    		return redirect('dist/secundaria')->withErrors("ERROR EL SECUNDARIA NO EXISTE CODE-0007");
    	}

        //return $compania;

    	 view()->share('secundaria', $secundaria);

    	return \View::make('dist/secundaria/mostrar');
    }
    public function Desactivar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return response()
              ->json(['response' => false]);
    	}*/
    	
    	$secundariaExiste = Secundaria::where('id', $this->request->secundariaId)
    					//->where('distribuidorId', Auth::user()->distribuidorId)
    					->first();
		if(!empty($secundariaExiste)){

			$estatus = 'Inactivo';
			if($secundariaExiste->estatus == 'Inactivo'){
				$estatus = 'Activo';	
			}

			$affectedRows = Secundaria::where('id', '=', $this->request->secundariaId)
							->update(['estatus' => $estatus]);
			
			return response()
              ->json(['response' => TRUE]);
		}

		return response()
              ->json(['response' => false]);
    }

	public function Importar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/
    	
		view()->share('data',Array()); 
    	//return \View::make('dist/impresora/importar');

    	return \View::make('dist/secundaria/importar');
    }


	public function PostImportar(){
		
		$data = Array();
		$data['errorList'] = [];
		$data['successList'] = [];
		

		if (!$this->request->hasFile('archivoPlantilla')) {
			return redirect('dist/secundaria/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTE CODE-0008");
		}

		$file = $this->request->file('archivoPlantilla');
		$extension = $file->getClientOriginalExtension();
			
		if($extension != 'xls' && $extension != 'xlsx'){
			return redirect('dist/secundaria/importar')->withErrors("NO SE HA PODIDO PROCESAR EL ARCHIVO SOLO SE PERMITE ARCHIVOS xls y xlsx CODE-0009");
		}

		$filename = time().uniqid().'.'.$extension;	
		$destinationPath = public_path() . '/importarexcel/';
		if( !is_dir($destinationPath) ){
		 	if(mkdir($destinationPath, 0775, true)){
		 		chmod($destinationPath, 0775);
			}else{
				return redirect('dist/secundaria/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTE CODE-0010");
			}
	 	}

	 	$upload = $this->request->file('archivoPlantilla')->move($destinationPath, $filename);
			
		if($file->getError() != 0){
			return redirect('dist/secundaria/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTEO CODE-0011");
		}

		$fileUrl = $destinationPath.$filename;

		//$import = new SecundariaImport();
		//Excel::import($import,  $fileUrl);
		// you can do whatever you want with the rows you already got
		//dd($import->rows);

		try {
			
			//Excel::import(new SecundariaImport, $fileUrl);

			$data['successMensaje'] = 'EL ARCHIVO HA SIDO VERIFICADO, Y SE IMPORTO CORRECTAMENTE';

		} catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
			 
			$failures = $e->failures();
			 
			 foreach ($failures as $failure) {
				 $failure->row(); // row that went wrong
				 $failure->attribute(); // either heading key (if using heading row concern) or column index
				 $failure->errors(); // Actual error messages from Laravel validator
				 $failure->values(); // The values of the row that has failed.

				 $data['errorList'][] = [
					'row' =>  $failure->row(),
					//'codigo' => $failure->values(),
					'mensaje' => 'El cebececo esta repetido o ya fue ingresado anteriormente'
				];
			 }
		}

		/*$secundaria = DB::table('bo_kpi_secundaria')
		->select('bo_kpi_secundaria.*')
		->get();
		return $secundaria;*/
		
		//return $data;

		view()->share('data', $data);
		return \View::make('dist/secundaria/importar');

	}

	public function secundariaCuentaIndex(){

		return \View::make('dist/secundariacuenta/index');

	}

	public function secundariaCuentaPostIndex(){
    			
		$request = $this->request->all();
		$columnsOrder = isset($request['order'][0]['column']) ? $request['order'][0]['column'] : '0';
		$orderBy=isset($request['columns'][$columnsOrder]['data']) ? $request['columns'][$columnsOrder]['data'] : 'id';
		$order = isset($request['order'][0]['dir']) ? $request['order'][0]['dir'] : 'ASC';
		$length = isset($request['length']) ? $request['length'] : '15';

		$currentPage = $request['currentPage'];  
		Paginator::currentPageResolver(function() use ($currentPage){
			return $currentPage;
		});

		$query = DB::table('bo_kpi_secundaria')
		 ->select('bo_kpi_secundaria.*')
         ->orderBy($orderBy,$order);

		/*if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where('bo_kpi_secundaria.nombre_segmento', 'like', '%'.trim($request['searchInput']).'%');
		}*/

        if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where(
				function ($query) use ($request) {
					$query->orWhere('bo_kpi_secundaria.nombre', 'like', '%'.trim($request['searchInput']).'%');
                    $query->orWhere('bo_kpi_secundaria.codeGeneral', 'like', '%'.trim($request['searchInput']).'%');

				}
			 );		
		}
		   
		$secundaria = $query->paginate($length); 
	
		$result = $secundaria->toArray();
		$data = array();
		foreach($result['data'] as $value){

			if($value->estatus == 'Activo'){
				$detalle = '<a href="/dist/secundaria/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/secundaria/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-danger text-white m-b-5 desactivar"> <i class="bi bi-trash"></i> </a>';
			}else{
				$detalle = '<a href="/dist/secundaria/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/secundaria/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-primary text-white m-b-5 desactivar"> <i class="bi bi-check2-square"></i> </a>';
			}

			$data[] = array(
				  "DT_RowId" => $value->id,
				  "id" => $value->id,
				  "nombre"=> $value->nombre,
                  "codeGeneral"=> $value->codeGeneral,
				  "estatus"=> $value->estatus,
				  "detalle"=> $detalle
			);
		}

		$response = array(
				'draw' => isset($request['draw']) ? $request['draw'] : '1',
				'recordsTotal' => $result['total'],
				'recordsFiltered' => $result['total'],
				'data' => $data,
			);
		return response()
              ->json($response);
    }


	public function Listadoagregarsecundaria(){

		$request = $this->request->all();

		$columnsOrder = isset($request['order'][0]['column']) ? $request['order'][0]['column'] : '0';
		$orderBy=isset($request['columns'][$columnsOrder]['data']) ? $request['columns'][$columnsOrder]['data'] : 'id';
		$order = isset($request['order'][0]['dir']) ? $request['order'][0]['dir'] : 'ASC';
		$length = isset($request['length']) ? $request['length'] : '15';

		$currentPage = $request['currentPage'];  
		Paginator::currentPageResolver(function() use ($currentPage){
			return $currentPage;
		});

		$generalesCodigo = $request['_generalesCodigo'];  

         $query = DB::table('bo_kpi_secundaria')
		//->leftjoin('distribuidor', 'distribuidor.id', '=', 'cliente.distribuidorId')
		->where('bo_kpi_secundaria.codeGeneral', '=', $generalesCodigo)
		->where('bo_kpi_secundaria.estatus', '=', 'Activo')
		->select('bo_kpi_secundaria.codeSecundaria','bo_kpi_secundaria.id', 'bo_kpi_secundaria.nombre')
		 ->orderBy($orderBy,$order);;


		if(trim($request['searchInput']) != ""){
			$query->where(
				function ($query) use ($request) {
					$query->orWhere('bo_kpi_secundaria.nombre', 'like', '%'.trim($request['searchInput']).'%');
					$query->orWhere('bo_kpi_secundaria.codeGeneral', 'like', '%'.trim($request['searchInput']).'%');
					//$query->orWhere('cliente.email', 'like', '%'.trim($request['searchInput']).'%');
					//$query->orWhere('cliente.razonSocial', 'like', '%'.trim($request['searchInput']).'%');
				}
			 );	 
		}
		   
		$clientes = $query->paginate($length); 
	
		$result = $clientes->toArray();
		$data = array();
		foreach($result['data'] as $value){
			$data[] = array(
				  "DT_RowId" => $value->id,
				  "nombre"=> $value->nombre,
				  "codigo"=> $value->codeSecundaria,
				  "detalle"=> '<a href="#" attr-id="'.$value->id.'" attr-codigo="'.$value->codeSecundaria.'"  attr-nombre="'.$value->nombre.'"  class="agregarSecundariaModal"> <i class="fa fa-plus"></i> </a>'
			);
		}

		$response = array(
				'draw' => isset($request['draw']) ? $request['draw'] : '1',
				'recordsTotal' => $result['total'],
				'recordsFiltered' => $result['total'],
				'data' => $data,
			);
		return response()
              ->json($response);
				
    }

}
