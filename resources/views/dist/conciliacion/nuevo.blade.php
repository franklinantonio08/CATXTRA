@section('scripts')

<script>
	var token = '{{ csrf_token() }}';
</script>
	
<script type="text/javascript" src="{{ asset('../js/dist/compania/compania.js') }}"></script>


@stop

@extends('layouts.admin')

@section('content')
   

	<div class="col-lg-12">
        <div class="card mb-4">
			<div class="card-body p-4">
			<div class="row">
				<div class="col">
				<div class="card-title fs-4 fw-semibold">Compania</div>
				<!--<div class="card-subtitle text-disabled mb-4">1.232.150 registered users</div> -->
				</div>
				<div class="col-auto ms-auto">
					<button class="btn btn-info">
					<svg class="icon me-2">
					<use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-user-plus') }}"></use>
					</svg>Agregar Nueva
					</button>
				</div>
			</div>


			<div class="table-responsive">

                    <!-- ACTION BUTTONS -->
                    <div class="row">

                        @include('includes/errors')
                        @include('includes/success')

                            <form id="nuevoregistro" name="nuevoregistro" method="POST" action="{{ url()->current('/dist/compania/nuevo') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-sm-12">
                                        
                                        <ul class="nav nav-tabs navtab-custom nav-justified"  id="myTab">
                                            <li class="active tab">
                                                <a id='generalesTab' style="text-align: left !important;" href="#generales" data-toggle="tab" aria-expanded="false"  >
                                                    <span class="visible-xs"><i class="fa fa-home"></i></span>
                                                    <span class="hidden-xs ">Datos Generales</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <!-- INICIO TAB GENERALES -->
                                            <div class="tab-pane active" id="generales">
                                                <div class="table-responsive">
                                                        <div class="col-sm-5">
                                                            <div class="col-sm-12">
                                                                <div class="input-group m-b-10">
                                                                    <span class="input-group-addon">Nombre<span class="text-danger">&nbsp;*</span></span>
                                                                    <input type="text" id="nombre" name="nombre" value="" class="form-control maskLetraNumerosEspacio text-right" placeholder="">
                                                                </div>
                                                            </div>
                                                        
                                                        <div class="col-sm-5">
                                                            
                                
                                                        </div>
                                                </div>
                                            </div>
                                            <!-- FINAL TAB GENERALES -->
                                            <!-- ACTION BUTTONS -->
                                                <div class="row line-top m-t-10">
                                                    <div class="col-sm-12 m-t-20">
                                                        <div class="form-inline">
                                                            <button id="submitForm" name="submitForm" type="submit" class="btn btn-success m-b-5"> <i class="fa fa-check m-r-5"></i> <span>Guardar</span>
                                                            </button>
                                                            <a href="{{ url()->previous() }}" class="btn btn-inverse m-b-5"> <i class="fa fa-remove m-r-5"></i> <span>Cancelar</span></a>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            <!-- end ACTION BUTTONS -->
                                            
                                        
                                        </div>
                                    
                                        
                                    </div>
                                </div>
                                </form>

                    </div>
                    <!-- ACTION BUTTONS -->


			</div>
        </div>
	</div>    
</div>  



</div>
@endsection



