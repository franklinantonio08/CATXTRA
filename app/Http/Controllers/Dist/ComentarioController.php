<?php

namespace App\Http\Controllers\Dist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Imports\ComentarioImport;

use App\Models\Comentario;

use DB;
use Excel;


class ComentarioController extends Controller
{
    

    private $request;
	private $common;

    public function __construct(Request $request){
        $this->request = $request;
        //$this->common = new Common;
    }

    public function Index(){

        return \View::make('dist/comentario/index');

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

		$query = DB::table('bo_kpi_comments')
		 ->select('bo_kpi_comments.*')
         ->orderBy($orderBy,$order);

		/*if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where('bo_kpi_comments.nombre_segmento', 'like', '%'.trim($request['searchInput']).'%');
		}*/

        if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where(
				function ($query) use ($request) {
					$query->orWhere('bo_kpi_comments.commentTitle', 'like', '%'.trim($request['searchInput']).'%');
                    //$query->orWhere('bo_kpi_comments.codeComentario', 'like', '%'.trim($request['searchInput']).'%');

				}
			 );		
		}
		   
		$comentario = $query->paginate($length); 
	
		$result = $comentario->toArray();
		$data = array();
		foreach($result['data'] as $value){

			if($value->estatus == 'Activo'){
				$detalle = '<a href="/dist/comentario/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/comentario/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-danger text-white m-b-5 desactivar"> <i class="bi bi-trash"></i> </a>';
			}else{
				$detalle = '<a href="/dist/comentario/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-warning text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/comentario/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-primary text-white m-b-5 desactivar"> <i class="bi bi-check2-square"></i> </a>';
			}

			$data[] = array(
				  "DT_RowId" => $value->id,
				  "id" => $value->id,
				  "nombre"=> $value->commentTitle,
                  //"codeComentario"=> $value->codeComentario,
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
    	
    	return \View::make('dist/comentario/nuevo');
    }

    public function postNuevo(){
    	
        /*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

        //return $this->request->all();

    	$comentarioExiste = Comentario::where('nombre', $this->request->nombre)
        //->where('distribuidorId', Auth::user()->distribuidorId)
        ->first();
    	if(!empty($comentarioExiste)){
    		return redirect('dist/comentario/nuevo')->withErrors("ERROR AL GUARDAR EL GASTO CODE-0001");
    	}

		DB::beginTransaction();
		try { 	
			$comentario = new Comentario;
			$comentario->nombre         = trim($this->request->nombre);
            

			//if(isset($this->request->codDireccion)){
			//$comentario->cod_direccion    = trim($this->request->codDireccion);
			//}

			if(isset($this->request->comentario)){
				$comentario->comentario       = trim($this->request->comentario); 
			}
			
			$comentario->estatus          = 'Activo';
			$comentario->created_at       = date('Y-m-d H:i:s');
			$comentario->usuarioId        = Auth::user()->id;
			$result = $comentario->save();

            /*$comentarioId = $comentario->id;

            $comentarioCode = str_pad($comentarioId, 4, "0",STR_PAD_LEFT);

			$comentarioUpdate = Comentario::find($comentarioId);
			$comentarioUpdate->codeComentario = $comentarioCode;
			$result = $comentarioUpdate->save();	*/	


		} catch(\Illuminate\Database\QueryException $ex){ 
			DB::rollBack();
			return redirect('dist/comentario/nuevo')->withErrors('ERROR AL GUARDAR EL GASTO CODE-0002'.$ex);
		}
		
		if($result != 1){
			DB::rollBack();
			return redirect('dist/comentario/nuevo')->withErrors("ERROR AL GUARDAR EL GASTO CODE-0003");
		}
		DB::commit();

		return redirect('dist/comentario')->with('alertSuccess', 'EL GASTO HA SIDO INGRESADA');
    }

    public function Editar($comentarioId){
		/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$comentario = DB::table('bo_kpi_comments')
		 ->where('bo_kpi_comments.id', '=', $comentarioId)
		 //->where('rubro.distribuidorId', Auth::user()->distribuidorId)
		 ->select('bo_kpi_comments.*')->first();

    	if(empty($comentario)){
    		return redirect('dist/comentario')->withErrors("ERROR EL GASTO NO EXISTE CODE-0004");
    	}

    	view()->share('comentario', $comentario);
    	return \View::make('dist/comentario/editar');
    }

    public function PostEditar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$request = $this->request->all();

        //return $request;

    	$comentarioId = isset($this->request->comentarioId) ? $this->request->comentarioId: '';

        //return $companiaId;

    	$comentario = Comentario::where('id', $comentarioId)
        //->where('distribuidorId',Auth::user()->distribuidorId)
        ->first();

    	if(empty($comentario)){
    		return redirect('dist/comentario')->withErrors("ERROR EL GASTO NO EXISTE CODE-0005");
    	}

		DB::beginTransaction();
	    	$comentarioUpdate = Comentario::find($comentarioId);
			$comentarioUpdate->nombre          = $this->request->nombre;
            $comentarioUpdate->comentario      = $this->request->comentario;
            $result = $comentarioUpdate->save();

		if($result != 1){
			DB::rollBack();

			return redirect('dist/comentario/editar/'.$comentarioId)->withErrors("ERROR AL EDITAR ELEMENTOS DE EL GASTO CODE-0006");
		}

		DB::commit();

		return redirect('dist/comentario/')->with('alertSuccess', 'EL GASTO HA SIDO EDITADO');
    }

    public function Mostrar($comentarioId){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$comentario = DB::table('bo_kpi_comments')
    	 ->where('bo_kpi_comments.id', '=', $comentarioId)
		 ->select('bo_kpi_comments.*')->first();

    	if(empty($comentario)){
    		return redirect('dist/comentario')->withErrors("ERROR EL GASTO NO EXISTE CODE-0007");
    	}

        //return $compania;

    	 view()->share('comentario', $comentario);

    	return \View::make('dist/comentario/mostrar');
    }
    public function Desactivar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return response()
              ->json(['response' => false]);
    	}*/
    	
    	$comentarioExiste = Comentario::where('id', $this->request->comentarioId)
    					//->where('distribuidorId', Auth::user()->distribuidorId)
    					->first();
		if(!empty($comentarioExiste)){

			$estatus = 'Inactivo';
			if($comentarioExiste->estatus == 'Inactivo'){
				$estatus = 'Activo';	
			}

			$affectedRows = Comentario::where('id', '=', $this->request->comentarioId)
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

    	return \View::make('dist/comentario/importar');
    }


	public function PostImportar(){
		
		$data = Array();
		$data['errorList'] = [];
		$data['successList'] = [];
		

		if (!$this->request->hasFile('archivoPlantilla')) {
			return redirect('dist/comentario/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTE CODE-0008");
		}

		$file = $this->request->file('archivoPlantilla');
		$extension = $file->getClientOriginalExtension();
			
		if($extension != 'xls' && $extension != 'xlsx'){
			return redirect('dist/comentario/importar')->withErrors("NO SE HA PODIDO PROCESAR EL ARCHIVO SOLO SE PERMITE ARCHIVOS xls y xlsx CODE-0009");
		}

		$filename = time().uniqid().'.'.$extension;	
		$destinationPath = public_path() . '/importarexcel/';
		if( !is_dir($destinationPath) ){
		 	if(mkdir($destinationPath, 0775, true)){
		 		chmod($destinationPath, 0775);
			}else{
				return redirect('dist/comentario/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTE CODE-0010");
			}
	 	}

	 	$upload = $this->request->file('archivoPlantilla')->move($destinationPath, $filename);
			
		if($file->getError() != 0){
			return redirect('dist/comentario/importar')->withErrors("HA OCURRIDO UN ERROR AL INTENTAR LEER EL ARCHIVO, INTENTAR NUEVAMENTEO CODE-0011");
		}

		$fileUrl = $destinationPath.$filename;

		//$import = new ComentarioImport();
		//Excel::import($import,  $fileUrl);
		// you can do whatever you want with the rows you already got
		//dd($import->rows);

		try {
			
			//Excel::import(new ComentarioImport, $fileUrl);

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

		/*$comentario = DB::table('bo_kpi_comments')
		->select('bo_kpi_comments.*')
		->get();
		return $comentario;*/
		
		//return $data;

		view()->share('data', $data);
		return \View::make('dist/comentario/importar');

	}

}
