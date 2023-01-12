@section('scripts')

<script>
	var token = '{{ csrf_token() }}';
</script>
	
<script type="text/javascript" src="{{ asset('../js/dist/secundaria/secundaria.js') }}"></script>

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
                            {{ csrf_field() }}
                        <div class="col-lg-6 m-b-10">

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="nombreGenerales" name="nombreGenerales" type="text" placeholder="nombreGenerales" readonly value="{{$secundaria->nombreGenerales}}"/>
                                    <label for="nombreGenerales">Clasificacion General</label>
                                </div>
                            
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="codeGeneral" name="codeGeneral" type="text" placeholder="codeGeneral" readonly value="{{$secundaria->codeGeneral}}"/>
                                    <label for="codeGeneral">Clasificacion General Codigo </label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="nombre" name="nombre" type="text" placeholder="Nombre" readonly value="{{$secundaria->nombre}}"/>
                                    <label for="nombre">Clasificacion Secundaria</label>
                                </div>
                               
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="codeSecundaria" name="codeSecundaria" type="text" placeholder="codeSecundaria" readonly value="{{$secundaria->codeSecundaria}}"/>
                                    <label for="codeSecundaria">Clasificacion Secundaria Codigo</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="estatus" name="estatus" type="text" placeholder="Estatus" readonly value="{{$secundaria->estatus}}"/>
                                    <label for="estatus">Estatus</label>
                                </div>
                                
                               <!-- <div class="form-floating mb-3">
                                    <textarea class="form-control" id="comentario" name="comentario" type="text" placeholder="Comentario" style="height: 10rem;" ></textarea>
                                    <label for="comentario">Comentario</label>
                                </div> -->

                                <!-- ACTION BUTTONS -->
                                    <div class="form-group row">
                                        <div class="offset-12 col-12">
                                            <a href="{{ url()->previous() }}"  class="btn btn-secondary text-white"><i class="fa fa-remove m-r-5"></i> Volver</a>
                                        </div>
                                    </div>
                                <!-- end ACTION BUTTONS -->

                               
                        </div>
                </div>
            
                <!-- Fin Formulario-->

            </div>
	    </div>    
    </div>  



</div>

@endsection



