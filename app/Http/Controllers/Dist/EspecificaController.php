<?php

namespace App\Http\Controllers\Dist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Imports\EspecificaImport;

use App\Models\Especifica;

use DB;
use Excel;

class EspecificaController extends Controller
{
    
    private $request;
	private $common;

    public function __construct(Request $request){
        $this->request = $request;
        //$this->common = new Common;
    }

    public function Index(){

        return \View::make('dist/especifica/index');

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

		$query = DB::table('bo_kpi_especifica')
		->leftjoin('bo_kpi_general', 'bo_kpi_general.codeGeneral', '=', 'bo_kpi_especifica.codeGeneral')
		->leftjoin('bo_kpi_secundaria', 'bo_kpi_secundaria.codeSecundaria', '=', 'bo_kpi_especifica.codeSecundaria')
		->select(
            'bo_kpi_general.id as idGeneral', 
			'bo_kpi_general.nombre as nombreGeneral',
            'bo_kpi_secundaria.id as idSecundaria', 
			'bo_kpi_secundaria.nombre as nombreSecundaria',
			'bo_kpi_especifica.*'
		)
         ->orderBy($orderBy,$order);
		
		/*if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where('bo_kpi_especifica.nombre_segmento', 'like', '%'.trim($request['searchInput']).'%');
		}*/

        if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where(
				function ($query) use ($request) {
					$query->orWhere('bo_kpi_especifica.nombre', 'like', '%'.trim($request['searchInput']).'%');
                    $query->orWhere('bo_kpi_especifica.codeGeneral', 'like', '%'.trim($request['searchInput']).'%');

				}
			 );		
		}
		   
		$especifica = $query->paginate($length); 
	
		$result = $especifica->toArray();
		$data = array();
		foreach($result['data'] as $value){

			if($value->estatus == 'Activo'){
				$detalle = '<a href="/dist/especifica/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/especifica/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-danger text-white m-b-5 desactivar"> <i class="bi bi-trash"></i> </a>';
			}else{
				$detalle = '<a href="/dist/especifica/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/especifica/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-primary text-white m-b-5 desactivar"> <i class="bi bi-check2-square"></i> </a>';
			}

			$data[] = array(
				  "DT_RowId" => $value->id,
				  "id" => $value->id,
				  "nombreGeneral" => $value->nombreGeneral,
				  "nombreSecundaria" => $value->nombreSecundaria,
				  "nombre"=> $value->nombre,
                  "codeEspecifica"=> $value->codeEspecifica,
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
    	
    	return \View::make('dist/especifica/nuevo');
    }

    public function postNuevo(){
    	
        /*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

        //return $this->request->all();

    	$especificaExiste = Especifica::where('nombre', $this->request->nombre)
        ->where('codeGeneral', $this->request->generalesCodigo)
        ->where('codeSecundaria', $this->request->secundariaCodigo)
        ->first();

    	if(!empty($especificaExiste)){
    		return redirect('dist/especifica/nuevo')->withErrors("ERROR AL GUARDAR EL ESPECIFICA CODE-0001");
    	}

        if(empty($this->request->generalesCodigo)){
    		return redirect('dist/especifica/nuevo')->withErrors("ERROR AL GUARDAR EL ESPECIFICA CODE-0001");
    	}

        if(empty($this->request->secundariaCodigo)){
    		return redirect('dist/especifica/nuevo')->withErrors("ERROR AL GUARDAR EL ESPECIFICA CODE-0001");
    	}

		DB::beginTransaction();
		try { 	
			$especifica = new Especifica;
			$especifica->nombre         = trim($this->request->nombre);
			$especifica->codeGeneral    = trim($this->request->generalesCodigo);
			$especifica->codeSecundaria    = trim($this->request->secundariaCodigo);
            
			//if(isset($this->request->codDireccion)){
			//$especifica->cod_direccion    = trim($this->request->codDireccion);
			//}

			if(isset($this->request->comentario)){
				$especifica->comentario       = trim($this->request->comentario); 
			}
			
			$especifica->estatus          = 'Activo';
			$especifica->created_at       = date('Y-m-d H:i:s');
			$especifica->usuarioId        = Auth::user()->id;
			$result = $especifica->save();

            $especificaId = $especifica->id;

            $codeEspecifica = str_pad($especificaId, 4, "0",STR_PAD_LEFT);

			$especificaUpdate = Especifica::find($especificaId);
			$especificaUpdate->codeEspecifica = $codeEspecifica;
			$result = $especificaUpdate->save();		


		} catch(\Illuminate\Database\QueryException $ex){ 
			DB::rollBack();
			return redirect('dist/especifica/nuevo')->withErrors('ERROR AL GUARDAR EL ESPECIFICA CODE-0002'.$ex);
		}
		
		if($result != 1){
			DB::rollBack();
			return redirect('dist/especifica/nuevo')->withErrors("ERROR AL GUARDAR EL ESPECIFICA CODE-0003");
		}
		DB::commit();

		return redirect('dist/especifica')->with('alertSuccess', 'EL ESPECIFICA HA SIDO INGRESADA');
    }

    public function Editar($especificaId){
		/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

		$especifica = DB::table('bo_kpi_especifica')
		->where('bo_kpi_especifica.id', '=', $especificaId)
		//->select('bo_kpi_especifica.*')
		//->first();
		->leftjoin('bo_kpi_general', 'bo_kpi_general.codeGeneral', '=', 'bo_kpi_especifica.codeGeneral')
		->select('bo_kpi_general.id as idGeneral', 
		'bo_kpi_general.nombre as nombreGenerales',
		'bo_kpi_especifica.*'
		)
		->first();

/*
    	$especifica = DB::table('bo_kpi_especifica')
		 ->where('bo_kpi_especifica.id', '=', $especificaId)
		 //->where('rubro.distribuidorId', Auth::user()->distribuidorId)
		 ->leftjoin('bo_kpi_general', 'bo_kpi_general.codeGeneral', '=', 'bo_kpi_especifica.codeGeneral')
		 ->select('bo_kpi_especifica.*')->first(); */

    	if(empty($especifica)){
    		return redirect('dist/especifica')->withErrors("ERROR EL ESPECIFICA NO EXISTE CODE-0004");
    	}

    	view()->share('especifica', $especifica);
    	return \View::make('dist/especifica/editar');
    }

    public function PostEditar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$request = $this->request->all();

        //return $request;

    	$especificaId = isset($this->request->especificaId) ? $this->request->especificaId: '';

        //return $companiaId;

    	$especifica = Especifica::where('id', $especificaId)
        //->where('distribuidorId',Auth::user()->distribuidorId)
        ->first();

    	if(empty($especifica)){
    		return redirect('dist/especifica')->withErrors("ERROR EL ESPECIFICA NO EXISTE CODE-0005");
    	}

		DB::beginTransaction();
	    	$especificaUpdate = Especifica::find($especificaId);
			$especificaUpdate->nombre          = $this->request->nombre;
            $especificaUpdate->comentario      = $this->request->comentario;
            $result = $especificaUpdate->save();

		if($result != 1){
			DB::rollBack();

			return redirect('dist/especifica/editar/'.$especificaId)->withErrors("ERROR AL EDITAR ELEMENTOS DE EL ESPECIFICA CODE-0006");
		}

		DB::commit();

		return redirect('dist/especifica/')->with('alertSuccess', 'EL ESPECIFICA HA SIDO EDITADO');
    }

    public function Mostrar($especificaId){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$especifica = DB::table('bo_kpi_especifica')
		->where('bo_kpi_especifica.id', '=', $especificaId)
		//->select('bo_kpi_especifica.*')
		//->first();
		->leftjoin('bo_kpi_general', 'bo_kpi_general.codeGeneral', '=', 'bo_kpi_especifica.codeGeneral')
		->select('bo_kpi_general.id as idGeneral', 
		'bo_kpi_general.nombre as nombreGenerales',
		'bo_kpi_especifica.*'
		)
		->first();

    	if(empty($especifica)){
    		return redirect('dist/especifica')->withErrors("ERROR EL ESPECIFICA NO EXISTE CODE-0007");
    	}

        //return $compania;

    	 view()->share('especifica', $especifica);

    	return \View::make('dist/especifica/mostrar');
    }
    public function Desactivar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return response()
              ->json(['response' => false]);
    	}*/
    	
    	$especificaExiste = Especifica::where('id', $this->request->especificaId)
    					//->where('distribuidorId', Auth::user()->distribuidorId)
    					->first();
		if(!empty($especificaExiste)){

			$estatus = 'Inactivo';
			if($especificaExiste->estatus == 'Inactivo'){
				$estatus = 'Activo';	
			}

			$affectedRows = Especifica::where('id', '=', $this->request->especificaId)
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

    	return \View::make('dist/especifica/importar');
    }


	public function PostImportar(){
		
		$data = Array();
		$data['errorList'] = [];
		$data['successList'] = [];
		

		if (!$this->request->hasFile('archivoPlantilla')) {
			return redirect('dist/especifica/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTE CODE-0008");
		}

		$file = $this->request->file('archivoPlantilla');
		$extension = $file->getClientOriginalExtension();
			
		if($extension != 'xls' && $extension != 'xlsx'){
			return redirect('dist/especifica/importar')->withErrors("NO SE HA PODIDO PROCESAR EL ARCHIVO SOLO SE PERMITE ARCHIVOS xls y xlsx CODE-0009");
		}

		$filename = time().uniqid().'.'.$extension;	
		$destinationPath = public_path() . '/importarexcel/';
		if( !is_dir($destinationPath) ){
		 	if(mkdir($destinationPath, 0775, true)){
		 		chmod($destinationPath, 0775);
			}else{
				return redirect('dist/especifica/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTE CODE-0010");
			}
	 	}

	 	$upload = $this->request->file('archivoPlantilla')->move($destinationPath, $filename);
			
		if($file->getError() != 0){
			return redirect('dist/especifica/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTEO CODE-0011");
		}

		$fileUrl = $destinationPath.$filename;

		//$import = new EspecificaImport();
		//Excel::import($import,  $fileUrl);
		// you can do whatever you want with the rows you already got
		//dd($import->rows);

		try {
			
			//Excel::import(new EspecificaImport, $fileUrl);

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

		/*$especifica = DB::table('bo_kpi_especifica')
		->select('bo_kpi_especifica.*')
		->get();
		return $especifica;*/
		
		//return $data;

		view()->share('data', $data);
		return \View::make('dist/especifica/importar');

	}

	public function especificaCuentaIndex(){

		return \View::make('dist/especificacuenta/index');

	}

	public function especificaCuentaPostIndex(){
    			
		$request = $this->request->all();
		$columnsOrder = isset($request['order'][0]['column']) ? $request['order'][0]['column'] : '0';
		$orderBy=isset($request['columns'][$columnsOrder]['data']) ? $request['columns'][$columnsOrder]['data'] : 'id';
		$order = isset($request['order'][0]['dir']) ? $request['order'][0]['dir'] : 'ASC';
		$length = isset($request['length']) ? $request['length'] : '15';

		$currentPage = $request['currentPage'];  
		Paginator::currentPageResolver(function() use ($currentPage){
			return $currentPage;
		});

		$query = DB::table('bo_kpi_especifica')
		 ->select('bo_kpi_especifica.*')
         ->orderBy($orderBy,$order);

		/*if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where('bo_kpi_especifica.nombre_segmento', 'like', '%'.trim($request['searchInput']).'%');
		}*/

        if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where(
				function ($query) use ($request) {
					$query->orWhere('bo_kpi_especifica.nombre', 'like', '%'.trim($request['searchInput']).'%');
                    $query->orWhere('bo_kpi_especifica.codeGeneral', 'like', '%'.trim($request['searchInput']).'%');

				}
			 );		
		}
		   
		$especifica = $query->paginate($length); 
	
		$result = $especifica->toArray();
		$data = array();
		foreach($result['data'] as $value){

			if($value->estatus == 'Activo'){
				$detalle = '<a href="/dist/especifica/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/especifica/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-danger text-white m-b-5 desactivar"> <i class="bi bi-trash"></i> </a>';
			}else{
				$detalle = '<a href="/dist/especifica/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/especifica/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
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
}
