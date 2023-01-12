@section('scripts')

<script>
	var token = '{{ csrf_token() }}';
</script>
	
<script type="text/javascript" src="{{ asset('../js/dist/secundaria/secundaria.js') }}"></script>
<script type="text/javascript" src="{{ asset('../js/comun/generalesModal.js') }}"></script>
<script type="text/javascript" src="{{ asset('../js/comun/messagebasicModal.js') }}"></script>

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
                        <div class="card-title fs-4 fw-semibold">Clasificación Secundaria</div>
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
                    <form id="nuevoregistro" name="nuevoregistro" method="POST" action="{{ url()->current('/dist/secundaria/nuevo') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                        <div class="col-lg-6 m-b-10">


                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Clasificacion General</span>
                                    </div>
                                    <input type="text" id="generalesNombre" name="generalesNombre" class="form-control" aria-label="Amount (to the nearest dollar)" readonly>
                                    <input type="hidden" id="generales" name="generales" class="form-control" value="" >
                                    <input type="hidden" id="generalesCodigo" name="generalesCodigo" class="form-control" value="" >
                                    <div class="input-group-append">
                                        <span class="input-group-btn">
                                            <button type="button" id="agregarGenerales" name="agregarGenerales" class="btn waves-effect waves-light btn-warning"><i class="fa fa-plus"></i></button>
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="sr-only" for="nombre"></label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Nombre</div>
                                        </div>
                                        <input type="text" class="form-control" id="nombre"  name="nombre" placeholder="">
                                    </div>
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
@include('includes/generaleslist')

@endsection



