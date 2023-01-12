<?php

namespace App\Http\Controllers\Dist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Imports\GeneralesImport;

use App\Models\Generales;

use DB;
use Excel;

class GeneralesController extends Controller
{
    
    private $request;
	private $common;

    public function __construct(Request $request){
        $this->request = $request;
        //$this->common = new Common;
    }

    public function Index(){

        return \View::make('dist/generales/index');

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

		$query = DB::table('bo_kpi_general')
		 ->select('bo_kpi_general.*')
         ->orderBy($orderBy,$order);

		/*if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where('bo_kpi_general.nombre_segmento', 'like', '%'.trim($request['searchInput']).'%');
		}*/

        if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where(
				function ($query) use ($request) {
					$query->orWhere('bo_kpi_general.nombre', 'like', '%'.trim($request['searchInput']).'%');
                    $query->orWhere('bo_kpi_general.codeGeneral', 'like', '%'.trim($request['searchInput']).'%');

				}
			 );		
		}
		   
		$generales = $query->paginate($length); 
	
		$result = $generales->toArray();
		$data = array();
		foreach($result['data'] as $value){

			if($value->estatus == 'Activo'){
				$detalle = '<a href="/dist/generales/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/generales/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-danger text-white m-b-5 desactivar"> <i class="bi bi-trash"></i> </a>';
			}else{
				$detalle = '<a href="/dist/generales/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/generales/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
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

    public function Nuevo(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/
    	
    	return \View::make('dist/generales/nuevo');
    }

    public function postNuevo(){
    	
        /*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

        //return $this->request->all();

    	$generalesExiste = Generales::where('nombre', $this->request->nombre)
        //->where('distribuidorId', Auth::user()->distribuidorId)
        ->first();
    	if(!empty($generalesExiste)){
    		return redirect('dist/generales/nuevo')->withErrors("ERROR AL GUARDAR EL GENERALES CODE-0001");
    	}

		DB::beginTransaction();
		try { 	
			$generales = new Generales;
			$generales->nombre         = trim($this->request->nombre);
            

			//if(isset($this->request->codDireccion)){
			//$generales->cod_direccion    = trim($this->request->codDireccion);
			//}

			if(isset($this->request->comentario)){
				$generales->comentario       = trim($this->request->comentario); 
			}
			
			$generales->estatus          = 'Activo';
			$generales->created_at       = date('Y-m-d H:i:s');
			$generales->usuarioId        = Auth::user()->id;
			$result = $generales->save();

            $generalesId = $generales->id;

            $codeGeneral = str_pad($generalesId, 4, "0",STR_PAD_LEFT);

			$generalesUpdate = Generales::find($generalesId);
			$generalesUpdate->codeGeneral = $codeGeneral;
			$result = $generalesUpdate->save();		


		} catch(\Illuminate\Database\QueryException $ex){ 
			DB::rollBack();
			return redirect('dist/generales/nuevo')->withErrors('ERROR AL GUARDAR EL GENERALES CODE-0002'.$ex);
		}
		
		if($result != 1){
			DB::rollBack();
			return redirect('dist/generales/nuevo')->withErrors("ERROR AL GUARDAR EL GENERALES CODE-0003");
		}
		DB::commit();

		return redirect('dist/generales')->with('alertSuccess', 'EL GENERALES HA SIDO INGRESADA');
    }

    public function Editar($generalesId){
		/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$generales = DB::table('bo_kpi_general')
		 ->where('bo_kpi_general.id', '=', $generalesId)
		 //->where('rubro.distribuidorId', Auth::user()->distribuidorId)
		 ->select('bo_kpi_general.*')->first();

    	if(empty($generales)){
    		return redirect('dist/generales')->withErrors("ERROR EL GENERALES NO EXISTE CODE-0004");
    	}

    	view()->share('generales', $generales);
    	return \View::make('dist/generales/editar');
    }

    public function PostEditar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$request = $this->request->all();

        //return $request;

    	$generalesId = isset($this->request->generalesId) ? $this->request->generalesId: '';

        //return $companiaId;

    	$generales = Generales::where('id', $generalesId)
        //->where('distribuidorId',Auth::user()->distribuidorId)
        ->first();

    	if(empty($generales)){
    		return redirect('dist/generales')->withErrors("ERROR EL GENERALES NO EXISTE CODE-0005");
    	}

		DB::beginTransaction();
	    	$generalesUpdate = Generales::find($generalesId);
			$generalesUpdate->nombre          = $this->request->nombre;
            $generalesUpdate->comentario      = $this->request->comentario;
            $result = $generalesUpdate->save();

		if($result != 1){
			DB::rollBack();

			return redirect('dist/generales/editar/'.$generalesId)->withErrors("ERROR AL EDITAR ELEMENTOS DE EL GENERALES CODE-0006");
		}

		DB::commit();

		return redirect('dist/generales/')->with('alertSuccess', 'EL GENERALES HA SIDO EDITADO');
    }

    public function Mostrar($generalesId){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$generales = DB::table('bo_kpi_general')
    	 ->where('bo_kpi_general.id', '=', $generalesId)
		 ->select('bo_kpi_general.*')->first();

    	if(empty($generales)){
    		return redirect('dist/generales')->withErrors("ERROR EL GENERALES NO EXISTE CODE-0007");
    	}

        //return $compania;

    	 view()->share('generales', $generales);

    	return \View::make('dist/generales/mostrar');
    }
    public function Desactivar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return response()
              ->json(['response' => false]);
    	}*/
    	
    	$generalesExiste = Generales::where('id', $this->request->generalesId)
    					//->where('distribuidorId', Auth::user()->distribuidorId)
    					->first();
		if(!empty($generalesExiste)){

			$estatus = 'Inactivo';
			if($generalesExiste->estatus == 'Inactivo'){
				$estatus = 'Activo';	
			}

			$affectedRows = Generales::where('id', '=', $this->request->generalesId)
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

    	return \View::make('dist/generales/importar');
    }


	public function PostImportar(){
		
		$data = Array();
		$data['errorList'] = [];
		$data['successList'] = [];
		

		if (!$this->request->hasFile('archivoPlantilla')) {
			return redirect('dist/generales/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTE CODE-0008");
		}

		$file = $this->request->file('archivoPlantilla');
		$extension = $file->getClientOriginalExtension();
			
		if($extension != 'xls' && $extension != 'xlsx'){
			return redirect('dist/generales/importar')->withErrors("NO SE HA PODIDO PROCESAR EL ARCHIVO SOLO SE PERMITE ARCHIVOS xls y xlsx CODE-0009");
		}

		$filename = time().uniqid().'.'.$extension;	
		$destinationPath = public_path() . '/importarexcel/';
		if( !is_dir($destinationPath) ){
		 	if(mkdir($destinationPath, 0775, true)){
		 		chmod($destinationPath, 0775);
			}else{
				return redirect('dist/generales/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTE CODE-0010");
			}
	 	}

	 	$upload = $this->request->file('archivoPlantilla')->move($destinationPath, $filename);
			
		if($file->getError() != 0){
			return redirect('dist/generales/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTEO CODE-0011");
		}

		$fileUrl = $destinationPath.$filename;

		//$import = new GeneralesImport();
		//Excel::import($import,  $fileUrl);
		// you can do whatever you want with the rows you already got
		//dd($import->rows);

		try {
			
			//Excel::import(new GeneralesImport, $fileUrl);

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

		/*$generales = DB::table('bo_kpi_general')
		->select('bo_kpi_general.*')
		->get();
		return $generales;*/
		
		//return $data;

		view()->share('data', $data);
		return \View::make('dist/generales/importar');

	}

	public function generalesCuentaIndex(){

		return \View::make('dist/generalescuenta/index');

	}

	public function generalesCuentaPostIndex(){
    			
		$request = $this->request->all();
		$columnsOrder = isset($request['order'][0]['column']) ? $request['order'][0]['column'] : '0';
		$orderBy=isset($request['columns'][$columnsOrder]['data']) ? $request['columns'][$columnsOrder]['data'] : 'id';
		$order = isset($request['order'][0]['dir']) ? $request['order'][0]['dir'] : 'ASC';
		$length = isset($request['length']) ? $request['length'] : '15';

		$currentPage = $request['currentPage'];  
		Paginator::currentPageResolver(function() use ($currentPage){
			return $currentPage;
		});

		$query = DB::table('bo_kpi_general')
		 ->select('bo_kpi_general.*')
         ->orderBy($orderBy,$order);

		/*if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where('bo_kpi_general.nombre_segmento', 'like', '%'.trim($request['searchInput']).'%');
		}*/

        if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where(
				function ($query) use ($request) {
					$query->orWhere('bo_kpi_general.nombre', 'like', '%'.trim($request['searchInput']).'%');
                    $query->orWhere('bo_kpi_general.codeGeneral', 'like', '%'.trim($request['searchInput']).'%');

				}
			 );		
		}
		   
		$generales = $query->paginate($length); 
	
		$result = $generales->toArray();
		$data = array();
		foreach($result['data'] as $value){

			if($value->estatus == 'Activo'){
				$detalle = '<a href="/dist/generales/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/generales/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-danger text-white m-b-5 desactivar"> <i class="bi bi-trash"></i> </a>';
			}else{
				$detalle = '<a href="/dist/generales/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/generales/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
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

	public function Listadoagregargenerales(){

		$request = $this->request->all();
		$columnsOrder = isset($request['order'][0]['column']) ? $request['order'][0]['column'] : '0';
		$orderBy=isset($request['columns'][$columnsOrder]['data']) ? $request['columns'][$columnsOrder]['data'] : 'id';
		$order = isset($request['order'][0]['dir']) ? $request['order'][0]['dir'] : 'ASC';
		$length = isset($request['length']) ? $request['length'] : '15';

		$currentPage = $request['currentPage'];  
		Paginator::currentPageResolver(function() use ($currentPage){
			return $currentPage;
		});


         $query = DB::table('bo_kpi_general')
		//->leftjoin('distribuidor', 'distribuidor.id', '=', 'cliente.distribuidorId')
		->where('bo_kpi_general.estatus', '=', 'Activo')
		->select('bo_kpi_general.codeGeneral','bo_kpi_general.id', 'bo_kpi_general.nombre')
		 ->orderBy($orderBy,$order);;

		if(trim($request['searchInput']) != ""){
			$query->where(
				function ($query) use ($request) {
					$query->orWhere('bo_kpi_general.nombre', 'like', '%'.trim($request['searchInput']).'%');
					$query->orWhere('bo_kpi_general.codeGeneral', 'like', '%'.trim($request['searchInput']).'%');
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
				  "codigo"=> $value->codeGeneral,
				  "detalle"=> '<a href="#" attr-id="'.$value->id.'" attr-codigo="'.$value->codeGeneral.'"  attr-nombre="'.$value->nombre.'"  class="agregarGeneralesModal"> <i class="fa fa-plus"></i> </a>'
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
