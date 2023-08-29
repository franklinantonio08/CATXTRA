@section('scripts')

<script>
	var token = '{{ csrf_token() }}';
</script>
	
<script type="text/javascript" src="{{ asset('../js/dist/especifica/especifica.js') }}"></script>
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
                        <div class="card-title fs-4 fw-semibold">Clasificación Especifica</div>
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
                    <form id="nuevoregistro" name="nuevoregistro" method="POST" action="{{ url()->current('/dist/especifica/nuevo') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            
                            <input type="hidden" id="especificaId" name="especificaId" value="{{$especifica->id}}" class="form-control text-right" placeholder="">

                        <div class="col-lg-6 m-b-10">

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="nombreGenerales" name="nombreGenerales" type="text" placeholder="nombreGenerales" readonly value="{{$especifica->nombreGenerales}}"/>
                                    <label for="nombreGenerales">Nombre Generales</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="codeGeneral" name="codeGeneral" type="text" placeholder="codeGeneral" readonly value="{{$especifica->codeGeneral}}"/>
                                    <label for="codeGeneral">Codigo Generales</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="nombre" name="nombre" type="text" placeholder="nombre" value="{{$especifica->nombre}}"/>
                                    <label for="nombre">Nombre Especifica</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="codeEspecifica" name="codeEspecifica" type="text" placeholder="codeEspecifica" value="{{$especifica->codeEspecifica}}"/>
                                    <label for="codeEspecifica">Codigo Especifica</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="comentario" name="comentario" type="text" placeholder="Comentario" style="height: 10rem;" >{{$especifica->comentario}}</textarea>
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



