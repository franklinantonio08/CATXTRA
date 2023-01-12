<?php

namespace App\Http\Controllers\Dist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

use App\Models\Compania;

use DB;

class CompaniaController extends Controller
{

    private $request;
	private $common;

    public function __construct(Request $request){
        $this->request = $request;
        //$this->common = new Common;
    }

    public function Index(){

        return \View::make('dist/compania/index');

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

		$query = DB::table('compania')
		 ->select('compania.*')
         ->orderBy($orderBy,$order);

		if(isset($request['searchInput']) && trim($request['searchInput']) != ""){
			$query->where('compania.nombre', 'like', '%'.trim($request['searchInput']).'%');
		}
		   
		$articulos = $query->paginate($length); 
	
		$result = $articulos->toArray();
		$data = array();
		foreach($result['data'] as $value){

			if($value->estatus == 'Activo'){
				$detalle = '<a href="/dist/compania/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-primary text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/compania/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-danger text-white m-b-5 desactivar"> <i class="bi bi-trash"></i> </a>';
			}else{
				$detalle = '<a href="/dist/compania/mostrar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-primary text-white m-b-5"> <i class="bi bi-eye"></i> </a>
								<a href="/dist/compania/editar/'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-secondary text-white m-b-5"> <i class="bi bi-pencil"></i> </a>
								<a href="#" attr-id="'.$value->id.'" class="btn btn-icon waves-effect waves-light bg-success text-white m-b-5 desactivar"> <i class="bi bi-check2-square"></i> </a>';
			}

			$data[] = array(
				  "DT_RowId" => $value->id,
				  "id" => $value->id,
				  "nombre"=> $value->nombre,
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

        
    	
    	return \View::make('dist/compania/nuevo');
    }

    public function postNuevo(){
    	
        /*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$companiaExiste = Compania::where('nombre', $this->request->nombre)
        //->where('distribuidorId', Auth::user()->distribuidorId)
        ->first();
    	if(!empty($companiaExiste)){
    		return redirect('dist/compania/nuevo')->withErrors("ERROR AL GUARDAR LA COMPANIA CODE-0073");
    	}

		DB::beginTransaction();
		try { 	
			$compania = new Compania;
			//$compania->distribuidorId = Auth::user()->distribuidorId;
			$compania->nombre = trim($this->request->nombre);
			$compania->estatus = 'Activo';
			$compania->created_at = date('Y-m-d H:i:s');
			$compania->usuarioId = Auth::user()->id;
			$result = $compania->save();
		} catch(\Illuminate\Database\QueryException $ex){ 
			DB::rollBack();
			return redirect('dist/compania/nuevo')->withErrors('ERROR AL GUARDAR LA COMPANIA CODE-0074');
		}
		
		if($result != 1){
			DB::rollBack();
			return redirect('dist/compania/nuevo')->withErrors("ERROR AL GUARDAR LA COMPANIA CODE-0075");
		}
		DB::commit();

		return redirect('dist/compania')->with('alertSuccess', 'LA COMPANIA HA SIDO INGRESADO');
    }

    public function Editar($companiaId){
		/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$compania = DB::table('compania')
		 ->where('compania.id', '=', $companiaId)
		 //->where('rubro.distribuidorId', Auth::user()->distribuidorId)
		 ->select('compania.*')->first();

    	if(empty($compania)){
    		return redirect('dist/compania')->withErrors("ERROR LA COMPANIA NO EXISTE CODE-0077");
    	}

    	view()->share('compania', $compania);
    	return \View::make('dist/compania/editar');
    }

    public function PostEditar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$request = $this->request->all();

        //return $request;

    	$companiaId = isset($this->request->companiaId) ? $this->request->companiaId: '';

        //return $companiaId;

    	$compania = Compania::where('id', $companiaId)
        //->where('distribuidorId',Auth::user()->distribuidorId)
        ->first();

    	if(empty($compania)){
    		return redirect('dist/compania')->withErrors("ERROR LA COMPANIA NO EXISTE CODE-0078");
    	}

		DB::beginTransaction();
	    	$companiaUpdate = Compania::find($companiaId);
			$companiaUpdate->nombre = $this->request->nombre;
			$result = $companiaUpdate->save();

		if($result != 1){
			DB::rollBack();

			return redirect('dist/compania/editar/'.$companiaId)->withErrors("ERROR AL EDITAR ELEMENTOS DE LA COMPANIA CODE-0079");
		}

		DB::commit();

		return redirect('dist/compania/')->with('alertSuccess', 'LA COMPANIA HA SIDO EDITADO');
    }

    public function Mostrar($companiaId){
    	/*if(!$this->common->usuariopermiso('004')){
    		return redirect('dist/dashboard')->withErrors($this->common->message);
    	}*/

    	$compania = DB::table('compania')
    	 ->where('compania.id', '=', $companiaId)
		 ->select('compania.*')->first();

    	if(empty($compania)){
    		return redirect('dist/compania')->withErrors("ERROR LA COMPANIA NO EXISTE CODE-0076");
    	}

        //return $compania;

    	 view()->share('compania', $compania);

    	return \View::make('dist/compania/mostrar');
    }
    public function Desactivar(){
    	/*if(!$this->common->usuariopermiso('004')){
    		return response()
              ->json(['response' => false]);
    	}*/
    	
    	$companiaExiste = Compania::where('id', $this->request->companiaId)
    					//->where('distribuidorId', Auth::user()->distribuidorId)
    					->first();
		if(!empty($companiaExiste)){

			$estatus = 'Inactivo';
			if($companiaExiste->estatus == 'Inactivo'){
				$estatus = 'Activo';	
			}

			$affectedRows = Compania::where('id', '=', $this->request->companiaId)
							->update(['estatus' => $estatus]);
			
			return response()
              ->json(['response' => TRUE]);
		}

		return response()
              ->json(['response' => false]);
    }

    
}
