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

                        <div class="tab-content">
                            <!-- INICIO TAB GENERALES -->
                            <div class="tab-pane active" id="generales">
                
                                <div class="table-responsive">
                                    <div class="col-sm-5">
                                        
                                        <div class="col-sm-12">
                                            <div class="input-group m-b-10">
                                                <span class="input-group-addon">Nombre</span>
                                                <input type="text" id="nombre" name="nombre" readonly value="{{$compania->nombre}}" class="form-control  text-right" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="input-group m-b-10">
                                                <span class="input-group-addon">Estatus</span>
                                                <input type="text" id="userDist" name="userDist" readonly value="{{$compania->estatus}}" class="form-control  text-right" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="input-group m-b-10">
                                                <span class="input-group-addon">Fecha Creaci&oacute;n</span>
                                                <input type="text" id="fechaCreacion" name="fechaCreacion" readonly value="{{$compania->created_at}}" class="form-control  text-right" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                
                            </div>
                            <!-- FINAL TAB GENERALES -->
                <!-- ACTION BUTTONS -->
				<div class="row line-top m-t-10">
					<div class="col-sm-12 m-t-20">
						<div class="form-inline">
							<a href="{{ url()->previous() }}" class="btn btn-inverse m-b-5"> <span>Regresar</span></a>
						</div>
						
					</div>
				</div>
                        
                        </div>

                    </div>
                    <!-- ACTION BUTTONS -->


			</div>
        </div>
	</div>    
</div>  



</div>
@endsection



