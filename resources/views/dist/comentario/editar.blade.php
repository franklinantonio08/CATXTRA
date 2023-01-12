@section('scripts')

<script>
	var token = '{{ csrf_token() }}';
</script>
	
<script type="text/javascript" src="{{ asset('../js/dist/comentario/comentario.js') }}"></script>
<script src="{{ asset('../js/comun/messagebasicModal.js') }}"></script>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

@stop

@extends('layouts.admin')

@section('content')
   
	<div class="col-lg-12">
        <div class="card mb-4">
			
            <div class="card-body p-4">
                <div class="row">
                    <div class="col">
                        <div class="card-title fs-4 fw-semibold">Comentario</div>
                    </div>
                </div>
			</div>

            <div class="table-responsive">

                    <!-- ACTION BUTTONS -->
                    <div class="row">

                        @include('includes/errors')
                        @include('includes/success')

                    </div>
                   
                <!-- Formulario -->

                <div class="container px-2 my-2">
                    <form id="nuevoregistro" name="nuevoregistro" method="POST" action="{{ url()->current('/dist/comentario/nuevo') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            
                            <input type="hidden" id="comentarioId" name="comentarioId" value="{{$comentario->id}}" class="form-control text-right" placeholder="">

                        <div class="col-lg-6 m-b-10">

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="regional" name="regional" type="text" placeholder="Regional" value="{{$comentario->regional}}"/>
                                    <label for="regional">Regional</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="segmento" name="segmento" type="text" placeholder="Segmento" value="{{$comentario->segmento}}"/>
                                    <label for="segmento">Segmento</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="formato" name="formato" type="text" placeholder="Formato" value="{{$comentario->formato}}"/>
                                    <label for="formato">Formato</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="nombreSegmento" name="nombreSegmento" type="text" placeholder="Nombre Segmento" value="{{$comentario->nombre_segmento}}"/>
                                    <label for="nombreSegmento">Nombre Segmento</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="direccion" name="direccion" type="text" placeholder="Direccion" value="{{$comentario->direccion}}"/>
                                    <label for="direccion">Direccion</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="cebeCeco" name="cebeCeco" type="text" placeholder="CEBE CECO" value="{{$comentario->cebe_ceco}}"/>
                                    <label for="cebeCeco">CEBE CECO</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="nombreCebeCeco" name="nombreCebeCeco" type="text" placeholder="Nombre CEBE CECO" value="{{$comentario->nombre_cebe_ceco}}"/>
                                    <label for="nombreCebeCeco">Nombre CEBE CECO</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="codRegion" name="codRegion" type="text" placeholder="Cod Region" value="{{$comentario->cod_region}}"/>
                                    <label for="codRegion">Cod Region</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="codFormato" name="codFormato" type="text" placeholder="Cod Formato" value="{{$comentario->cod_formato}}"/>
                                    <label for="codFormato">Cod Formato</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="codDireccion" name="codDireccion" type="text" placeholder="Cod Direccion" value="{{$comentario->cod_direccion}}"/>
                                    <label for="codDireccion">Cod Direccion</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="segAdministrado" name="segAdministrado" type="text" placeholder="Seg Administrado" value="{{$comentario->seg_administrado}}"/>
                                    <label for="segAdministrado">Seg Administrado</label>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="comentario" name="comentario" type="text" placeholder="Comentario" style="height: 10rem;" ></textarea>
                                    <label for="comentario">Comentario</label>
                                </div>

                                <!-- ACTION BUTTONS -->
                                    <div class="form-group row">
                                        <div class="offset-12 col-12">
                                            <button id="submitForm" name="submitForm" type="submit" class="btn btn-primary text-white"><i class="fa fa-check m-r-5"></i> Guardar</button>
                                            <a href="{{ url()->previous() }}"  class="btn btn-danger text-white"><i class="fa fa-remove m-r-5"></i> Cancelar</a>
                                        </div>
                                    </div>
                                <!-- end ACTION BUTTONS -->

                               
                        </div>
                    </form>
                </div>
            
                <!-- Fin Formulario-->

            </div>
	    </div>    
    </div>  



</div>

@include('includes/messagebasicmodal')
@endsection



