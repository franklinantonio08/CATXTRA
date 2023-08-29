@section('scripts')

<script>
	var token = '{{ csrf_token() }}';
</script>
	
<script type="text/javascript" src="{{ asset('../js/dist/storecebececo/storecebececo.js') }}"></script>
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
                        <div class="card-title fs-4 fw-semibold">Store Cebececo</div>
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

                @if (empty($data))

                <div class="container px-2 my-2">
                    <form id="nuevoimportar" name="nuevoimportar" method="POST" action="{{ url()->current('/dist/storecebececo/importar') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                        <div class="col-lg-6 m-b-10">

                                <div class="form-floating mb-3">
                                    <a href="{{ asset('../plantillas/storecebececo.xlsx') }}" class="btn btn-primary m-b-5"> <i class="fa fa-cloud-download m-r-5"></i> <span>Descargar plantilla storecebececo</span></a>
                                </div>

                              
                                <div class="input-group m-b-3">
                                    <label class="input-group-btn">
                                        <span class="btn btn-primary">
                                            <i class="fa fa-cloud-upload m-r-3"></i> Plantilla: <input id='archivoPlantilla' class="articuloImagen" name='archivoPlantilla' type="file" style="display: none;">
                                        </span>
                                    </label>
                                    
                                        <input type="text" id='imagenError' name='imagenError'  class="form-control" readonly>
                                    
                                </div>
                                    <label id="fileError"></label>
                                

                                <!-- ACTION BUTTONS -->
                                    <div class="form-group row">
                                        <div class="offset-12 col-12">
                                            <button id="submitForm" name="submitForm" type="submit" class="btn btn-primary text-white"><i class="fa fa-check m-r-5"></i> Validar</button>
                                            <a href="{{ url()->previous() }}"  class="btn btn-secondary text-white"><i class="fa fa-remove m-r-5"></i> Cancelar</a>
                                        </div>
                                    </div>
                                <!-- end ACTION BUTTONS -->

                               
                        </div>
                    </form>
                </div>

                @else

                
                <div class="container px-2 my-2"> 

                    <!-- <div class="col-lg-6 m-b-10"> -->
                        
                    @if (!empty($data['errorList']))
                
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th class="bg-primary fs-8 fw-semibold text-white"># Fila</th>
                                        <th class="bg-primary fs-8 fw-semibold text-white">Mensaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data["errorList"] as $value)
                                        <tr class="gradeX">
                                            <td>{{$value['row']}}</td>
                                            <td>{{$value['mensaje']}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @else

                        @if (!empty($data['successMensaje']))
                           
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col">
                                        <div class="card-title fs-5 fw-semibold">{{$data['successMensaje']}}</div>
                                    </div>
                                </div>
                            </div>

                        @endif

                    @endif

                 <!-- ACTION BUTTONS -->
                 <div class="form-group row">
                    <div class="offset-12 col-12">
                        <!-- <button id="submitForm" name="submitForm" type="submit" class="btn btn-primary text-white"><i class="fa fa-check m-r-5"></i> Validar</button> -->
                        <a href="{{ url()->previous() }}"  class="btn btn-secondary text-white"><i class="fa fa-remove m-r-5"></i> Cancelar</a>
                    </div>
                </div>
           
                <!-- end ACTION BUTTONS -->

                </div>

                @endif

                <!-- Fin Formulario-->

            </div>
	    </div>    
    </div>  



</div>

@include('includes/messagebasicmodal')
@endsection



