<?php

namespace App\Http\Controllers\Dist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Imports\StoreCebececoImport;

use App\Models\StoreCebececo;

use DB;
use Excel;

class StoreCebececoController extends Controller
{
    //

    private $request;
	private $common;

    public function __construct(Request $request){
        $this->request = $request;
        //$this->common = new Common;
    }

    public function Index(){

        return \View::make('dist/storecebececo/index');

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

		$query = DB::table('bo_kpi_store_cebececo')
		 ->select('bo_kpi_store_cebececo.*')
         ->orderBy($orderBy,$order);

		/*if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where('bo_kpi_store_cebececo.nombre_segmento', 'like', '%'.trim($request['searchInput']).'%');
		}*/

        if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where(
				function ($query) use ($request) {
					$query->orWhere('bo_kpi_store_cebececo.regional', 'like', '%'.trim($request['searchInput']).'%');
					$query->orWhere('bo_kpi_store_cebececo.segmento', 'like', '%'.trim($request['searchInput']).'%');
                    $query->orWhere('bo_kpi_store_cebececo.formato', 'like', date('Y-m-d',strtotime($request['searchInput'])));
					$query->orWhere('cliebo_kpi_store_cebececonte.nombre_segmento', 'like', '%'.trim($request['searchInput']).'%');
					$query->orWhere('bo_kpi_store_cebececo.direccion', 'like', '%'.trim($request['searchInput']).'%');
                    $query->orWhere('bo_kpi_store_cebececo.cebe_ceco', 'like', '%'.trim($request['searchInput']).'%');
					$query->orWhere('bo_kpi_store_cebececo.nombre_cebe_ceco', 'like', '%'.trim($request['searchInput']).'%');
				}
			 );		
		}
		   
		$storecebececos = $query->paginate($length); 
	
		$result = $storecebececos->toArray();
		$data = array();
		foreach($result['data'] as $value){

			if($value->estatus == 'Activo'){
				$detalle = '<a href="/dist/storecebececo/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/storecebececo/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-danger text-white m-b-5 desactivar"> <i class="bi bi-trash"></i> </a>';
			}else{
				$detalle = '<a href="/dist/storecebececo/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/storecebececo/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-primary text-white m-b-5 desactivar"> <i class="bi bi-check2-square"></i> </a>';
			}

			$data[] = array(
				  "DT_RowId" => $value->id,
				  "id" => $value->id,
				  "regional"=> $value->regional,
				  "formato"=> $value->formato,
				  "nombreSegmento"=> $value->nombre_segmento,
				  "direccion"=> $value->direccion,
				  "cebeCeco"=> $value->cebe_ceco,
				  "nombreCebeCeco"=> $value->nombre_cebe_ceco,
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
    	
    	return \View::make('dist/storecebececo/nuevo');
    }

    public function postNuevo(){
    	
        /*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

        //return $this->request->all();

    	$storecebececoExiste = StoreCebececo::where('cebe_ceco', $this->request->cebeCeco)
        //->where('distribuidorId', Auth::user()->distribuidorId)
        ->first();
    	if(!empty($storecebececoExiste)){
    		return redirect('dist/storecebececo/nuevo')->withErrors("ERROR AL GUARDAR STORE CEBECECO CODE-0001");
    	}

		DB::beginTransaction();
		try { 	
			$storecebececo = new StoreCebececo;
			$storecebececo->regional         = trim($this->request->regional);
            $storecebececo->segmento         = trim($this->request->segmento);
			$storecebececo->formato          = trim($this->request->formato);
			$storecebececo->nombre_segmento  = trim($this->request->nombreSegmento);
			$storecebececo->direccion        = trim($this->request->direccion);
			$storecebececo->cebe_ceco        = trim($this->request->cebeCeco);
			$storecebececo->nombre_cebe_ceco = trim($this->request->nombreCebeCeco);
			$storecebececo->cod_region       = trim($this->request->codRegion);
			$storecebececo->cod_formato      = trim($this->request->codFormato);

			if(isset($this->request->codDireccion)){
			$storecebececo->cod_direccion    = trim($this->request->codDireccion);
			}
			if(isset($this->request->comentario)){
				$storecebececo->comentario       = trim($this->request->comentario); 
			}
			

			$storecebececo->estatus          = 'Activo';
			$storecebececo->created_at       = date('Y-m-d H:i:s');
			$storecebececo->usuarioId        = Auth::user()->id;
			$result = $storecebececo->save();

		} catch(\Illuminate\Database\QueryException $ex){ 
			DB::rollBack();
			return redirect('dist/storecebececo/nuevo')->withErrors('ERROR AL GUARDAR STORE CEBECECO CODE-0002'.$ex);
		}
		
		if($result != 1){
			DB::rollBack();
			return redirect('dist/storecebececo/nuevo')->withErrors("ERROR AL GUARDAR STORE CEBECECO CODE-0003");
		}
		DB::commit();

		return redirect('dist/storecebececo')->with('alertSuccess', 'STORE CEBECECO HA SIDO INGRESADA');
    }

    public function Editar($storecebececoId){
		/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$storecebececo = DB::table('bo_kpi_store_cebececo')
		 ->where('bo_kpi_store_cebececo.id', '=', $storecebececoId)
		 //->where('rubro.distribuidorId', Auth::user()->distribuidorId)
		 ->select('bo_kpi_store_cebececo.*')->first();

    	if(empty($storecebececo)){
    		return redirect('dist/storecebececo')->withErrors("ERROR STORE CEBECECO NO EXISTE CODE-0004");
    	}

    	view()->share('storecebececo', $storecebececo);
    	return \View::make('dist/storecebececo/editar');
    }

    public function PostEditar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$request = $this->request->all();

        //return $request;

    	$storecebececoId = isset($this->request->storecebececoId) ? $this->request->storecebececoId: '';

        //return $companiaId;

    	$storecebececo = StoreCebececo::where('id', $storecebececoId)
        //->where('distribuidorId',Auth::user()->distribuidorId)
        ->first();

    	if(empty($storecebececo)){
    		return redirect('dist/storecebececo')->withErrors("ERROR STORE CEBECECO NO EXISTE CODE-0005");
    	}

		DB::beginTransaction();
	    	$storecebececoUpdate = StoreCebececo::find($storecebececoId);
			$storecebececoUpdate->regional          = $this->request->regional;
			$storecebececoUpdate->segmento          = $this->request->segmento;
			$storecebececoUpdate->formato           = $this->request->formato;
			$storecebececoUpdate->nombre_segmento   = $this->request->nombreSegmento;
            $storecebececoUpdate->direccion         = $this->request->direccion;
            $storecebececoUpdate->cebe_ceco         = $this->request->cebeCeco;
            $storecebececoUpdate->nombre_cebe_ceco  = $this->request->nombreCebeCeco;
            $storecebececoUpdate->cod_region        = $this->request->codRegion;
            $storecebececoUpdate->cod_formato       = $this->request->codFormato;
            $storecebececoUpdate->cod_direccion     = $this->request->codDireccion;
            $storecebececoUpdate->seg_administrado  = $this->request->segAdministrado;
            $storecebececoUpdate->comentario        = $this->request->comentario;
			
            $result = $storecebececoUpdate->save();

		if($result != 1){
			DB::rollBack();

			return redirect('dist/storecebececo/editar/'.$storecebececoId)->withErrors("ERROR AL EDITAR ELEMENTOS DE STORE CEBECECO CODE-0006");
		}

		DB::commit();

		return redirect('dist/storecebececo/')->with('alertSuccess', 'STORE CEBECECO HA SIDO EDITADO');
    }

    public function Mostrar($storecebececoId){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$storecebececo = DB::table('bo_kpi_store_cebececo')
    	 ->where('bo_kpi_store_cebececo.id', '=', $storecebececoId)
		 ->select('bo_kpi_store_cebececo.*')->first();

    	if(empty($storecebececo)){
    		return redirect('dist/storecebececo')->withErrors("ERROR STORE CEBECECO NO EXISTE CODE-0007");
    	}

        //return $compania;

    	 view()->share('storecebececo', $storecebececo);

    	return \View::make('dist/storecebececo/mostrar');
    }
    public function Desactivar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return response()
              ->json(['response' => false]);
    	}*/
    	
    	$storecebececoExiste = StoreCebececo::where('id', $this->request->storecebececoId)
    					//->where('distribuidorId', Auth::user()->distribuidorId)
    					->first();
		if(!empty($storecebececoExiste)){

			$estatus = 'Inactivo';
			if($storecebececoExiste->estatus == 'Inactivo'){
				$estatus = 'Activo';	
			}

			$affectedRows = StoreCebececo::where('id', '=', $this->request->storecebececoId)
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

    	return \View::make('dist/storecebececo/importar');
    }


	public function PostImportar(){
		
		$data = Array();
		$data['errorList'] = [];
		$data['successList'] = [];
		

		if (!$this->request->hasFile('archivoPlantilla')) {
			return redirect('dist/storecebececo/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTE CODE-0008");
		}

		$file = $this->request->file('archivoPlantilla');
		$extension = $file->getClientOriginalExtension();
			
		if($extension != 'xls' && $extension != 'xlsx'){
			return redirect('dist/storecebececo/importar')->withErrors("NO SE HA PODIDO PROCESAR EL ARCHIVO SOLO SE PERMITE ARCHIVOS xls y xlsx CODE-0009");
		}

		$filename = time().uniqid().'.'.$extension;	
		$destinationPath = public_path() . '/importarexcel/';
		if( !is_dir($destinationPath) ){
		 	if(mkdir($destinationPath, 0775, true)){
		 		chmod($destinationPath, 0775);
			}else{
				return redirect('dist/storecebececo/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTE CODE-0010");
			}
	 	}

	 	$upload = $this->request->file('archivoPlantilla')->move($destinationPath, $filename);
			
		if($file->getError() != 0){
			return redirect('dist/storecebececo/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTEO CODE-0012");
		}

		$fileUrl = $destinationPath.$filename;

		//$import = new StoreCebececoImport();
		//Excel::import($import,  $fileUrl);
		// you can do whatever you want with the rows you already got
		//dd($import->rows);

		try {
			
			Excel::import(new StoreCebececoImport, $fileUrl);

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

		/*$storecebececo = DB::table('bo_kpi_store_cebececo')
		->select('bo_kpi_store_cebececo.*')
		->get();
		return $storecebececo;*/
		
		//return $data;

		view()->share('data', $data);
		return \View::make('dist/storecebececo/importar');

	}

}
