<?php

namespace App\Http\Controllers\Dist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Imports\GastosImport;

use App\Models\Gastos;

use DB;
use Excel;

class GastosController extends Controller
{
    //

    private $request;
	private $common;

    public function __construct(Request $request){
        $this->request = $request;
        //$this->common = new Common;
    }

    public function Index(){

        return \View::make('dist/gastos/index');

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

		$query = DB::table('bi_kpi_gastos')
		 ->select('bi_kpi_gastos.*')
         ->orderBy($orderBy,$order);

		/*if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where('bi_kpi_gastos.nombre_segmento', 'like', '%'.trim($request['searchInput']).'%');
		}*/

        if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where(
				function ($query) use ($request) {
					$query->orWhere('bi_kpi_gastos.nombre', 'like', '%'.trim($request['searchInput']).'%');
                    $query->orWhere('bi_kpi_gastos.codeGastos', 'like', '%'.trim($request['searchInput']).'%');

				}
			 );		
		}
		   
		$gastos = $query->paginate($length); 
	
		$result = $gastos->toArray();
		$data = array();
		foreach($result['data'] as $value){

			if($value->estatus == 'Activo'){
				$detalle = '<a href="/dist/gastos/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/gastos/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-danger text-white m-b-5 desactivar"> <i class="bi bi-trash"></i> </a>';
			}else{
				$detalle = '<a href="/dist/gastos/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/gastos/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-primary text-white m-b-5 desactivar"> <i class="bi bi-check2-square"></i> </a>';
			}

			$data[] = array(
				  "DT_RowId" => $value->id,
				  "id" => $value->id,
				  "nombre"=> $value->nombre,
                  "codeGastos"=> $value->codeGastos,
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
    	
    	return \View::make('dist/gastos/nuevo');
    }

    public function postNuevo(){
    	
        /*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

        //return $this->request->all();

    	$gastosExiste = Gastos::where('nombre', $this->request->nombre)
        //->where('distribuidorId', Auth::user()->distribuidorId)
        ->first();
    	if(!empty($gastosExiste)){
    		return redirect('dist/gastos/nuevo')->withErrors("ERROR AL GUARDAR EL GASTO CODE-0001");
    	}

		DB::beginTransaction();
		try { 	
			$gastos = new Gastos;
			$gastos->nombre         = trim($this->request->nombre);
            

			//if(isset($this->request->codDireccion)){
			//$gastos->cod_direccion    = trim($this->request->codDireccion);
			//}

			if(isset($this->request->comentario)){
				$gastos->comentario       = trim($this->request->comentario); 
			}
			
			$gastos->estatus          = 'Activo';
			$gastos->created_at       = date('Y-m-d H:i:s');
			$gastos->usuarioId        = Auth::user()->id;
			$result = $gastos->save();

            $gastosId = $gastos->id;

            $gastosCode = str_pad($gastosId, 4, "0",STR_PAD_LEFT);

			$gastosUpdate = Gastos::find($gastosId);
			$gastosUpdate->codeGastos = $gastosCode;
			$result = $gastosUpdate->save();		


		} catch(\Illuminate\Database\QueryException $ex){ 
			DB::rollBack();
			return redirect('dist/gastos/nuevo')->withErrors('ERROR AL GUARDAR EL GASTO CODE-0002'.$ex);
		}
		
		if($result != 1){
			DB::rollBack();
			return redirect('dist/gastos/nuevo')->withErrors("ERROR AL GUARDAR EL GASTO CODE-0003");
		}
		DB::commit();

		return redirect('dist/gastos')->with('alertSuccess', 'EL GASTO HA SIDO INGRESADA');
    }

    public function Editar($gastosId){
		/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$gastos = DB::table('bi_kpi_gastos')
		 ->where('bi_kpi_gastos.id', '=', $gastosId)
		 //->where('rubro.distribuidorId', Auth::user()->distribuidorId)
		 ->select('bi_kpi_gastos.*')->first();

    	if(empty($gastos)){
    		return redirect('dist/gastos')->withErrors("ERROR EL GASTO NO EXISTE CODE-0004");
    	}

    	view()->share('gastos', $gastos);
    	return \View::make('dist/gastos/editar');
    }

    public function PostEditar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$request = $this->request->all();

        //return $request;

    	$gastosId = isset($this->request->gastosId) ? $this->request->gastosId: '';

        //return $companiaId;

    	$gastos = Gastos::where('id', $gastosId)
        //->where('distribuidorId',Auth::user()->distribuidorId)
        ->first();

    	if(empty($gastos)){
    		return redirect('dist/gastos')->withErrors("ERROR EL GASTO NO EXISTE CODE-0005");
    	}

		DB::beginTransaction();
	    	$gastosUpdate = Gastos::find($gastosId);
			$gastosUpdate->nombre          = $this->request->nombre;
            $gastosUpdate->comentario      = $this->request->comentario;
            $result = $gastosUpdate->save();

		if($result != 1){
			DB::rollBack();

			return redirect('dist/gastos/editar/'.$gastosId)->withErrors("ERROR AL EDITAR ELEMENTOS DE EL GASTO CODE-0006");
		}

		DB::commit();

		return redirect('dist/gastos/')->with('alertSuccess', 'EL GASTO HA SIDO EDITADO');
    }

    public function Mostrar($gastosId){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$gastos = DB::table('bi_kpi_gastos')
    	 ->where('bi_kpi_gastos.id', '=', $gastosId)
		 ->select('bi_kpi_gastos.*')->first();

    	if(empty($gastos)){
    		return redirect('dist/gastos')->withErrors("ERROR EL GASTO NO EXISTE CODE-0007");
    	}

        //return $compania;

    	 view()->share('gastos', $gastos);

    	return \View::make('dist/gastos/mostrar');
    }
    public function Desactivar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return response()
              ->json(['response' => false]);
    	}*/
    	
    	$gastosExiste = Gastos::where('id', $this->request->gastosId)
    					//->where('distribuidorId', Auth::user()->distribuidorId)
    					->first();
		if(!empty($gastosExiste)){

			$estatus = 'Inactivo';
			if($gastosExiste->estatus == 'Inactivo'){
				$estatus = 'Activo';	
			}

			$affectedRows = Gastos::where('id', '=', $this->request->gastosId)
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

    	return \View::make('dist/gastos/importar');
    }


	public function PostImportar(){
		
		$data = Array();
		$data['errorList'] = [];
		$data['successList'] = [];
		

		if (!$this->request->hasFile('archivoPlantilla')) {
			return redirect('dist/gastos/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTE CODE-0008");
		}

		$file = $this->request->file('archivoPlantilla');
		$extension = $file->getClientOriginalExtension();
			
		if($extension != 'xls' && $extension != 'xlsx'){
			return redirect('dist/gastos/importar')->withErrors("NO SE HA PODIDO PROCESAR EL ARCHIVO SOLO SE PERMITE ARCHIVOS xls y xlsx CODE-0009");
		}

		$filename = time().uniqid().'.'.$extension;	
		$destinationPath = public_path() . '/importarexcel/';
		if( !is_dir($destinationPath) ){
		 	if(mkdir($destinationPath, 0775, true)){
		 		chmod($destinationPath, 0775);
			}else{
				return redirect('dist/gastos/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTE CODE-0010");
			}
	 	}

	 	$upload = $this->request->file('archivoPlantilla')->move($destinationPath, $filename);
			
		if($file->getError() != 0){
			return redirect('dist/gastos/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTEO CODE-0011");
		}

		$fileUrl = $destinationPath.$filename;

		//$import = new GastosImport();
		//Excel::import($import,  $fileUrl);
		// you can do whatever you want with the rows you already got
		//dd($import->rows);

		try {
			
			//Excel::import(new GastosImport, $fileUrl);

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

		/*$gastos = DB::table('bi_kpi_gastos')
		->select('bi_kpi_gastos.*')
		->get();
		return $gastos;*/
		
		//return $data;

		view()->share('data', $data);
		return \View::make('dist/gastos/importar');

	}

	public function gastosCuentaIndex(){

		return \View::make('dist/gastoscuenta/index');

	}

	public function gastosCuentaPostIndex(){
    			
		$request = $this->request->all();
		$columnsOrder = isset($request['order'][0]['column']) ? $request['order'][0]['column'] : '0';
		$orderBy=isset($request['columns'][$columnsOrder]['data']) ? $request['columns'][$columnsOrder]['data'] : 'id';
		$order = isset($request['order'][0]['dir']) ? $request['order'][0]['dir'] : 'ASC';
		$length = isset($request['length']) ? $request['length'] : '15';

		$currentPage = $request['currentPage'];  
		Paginator::currentPageResolver(function() use ($currentPage){
			return $currentPage;
		});

		$query = DB::table('bi_kpi_gastos')
		 ->select('bi_kpi_gastos.*')
         ->orderBy($orderBy,$order);

		/*if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where('bi_kpi_gastos.nombre_segmento', 'like', '%'.trim($request['searchInput']).'%');
		}*/

        if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where(
				function ($query) use ($request) {
					$query->orWhere('bi_kpi_gastos.nombre', 'like', '%'.trim($request['searchInput']).'%');
                    $query->orWhere('bi_kpi_gastos.codeGastos', 'like', '%'.trim($request['searchInput']).'%');

				}
			 );		
		}
		   
		$gastos = $query->paginate($length); 
	
		$result = $gastos->toArray();
		$data = array();
		foreach($result['data'] as $value){

			if($value->estatus == 'Activo'){
				$detalle = '<a href="/dist/gastos/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/gastos/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-danger text-white m-b-5 desactivar"> <i class="bi bi-trash"></i> </a>';
			}else{
				$detalle = '<a href="/dist/gastos/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/gastos/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-primary text-white m-b-5 desactivar"> <i class="bi bi-check2-square"></i> </a>';
			}

			$data[] = array(
				  "DT_RowId" => $value->id,
				  "id" => $value->id,
				  "nombre"=> $value->nombre,
                  "codeGastos"=> $value->codeGastos,
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
