@section('scripts')

@stop

@extends('layouts.admin')

@section('content')
   
<!--
	<div class="col-lg-12">
        <div class="card mb-4">
			<div class="card-body p-4">
			<div class="row">
				<div class="col">
				<div class="card-title fs-4 fw-semibold">Users</div>
				<div class="card-subtitle text-disabled mb-4">1.232.150 registered users</div>
				</div>
				<div class="col-auto ms-auto">
					<button class="btn btn-secondary">
					<svg class="icon me-2">
					<use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user-plus"></use>
					</svg>Add new user
					</button>
				</div>
			</div>


			<div class="table-responsive">

			




			</div>
        </div>
	</div>    
</div>  -->

<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<h4 class="page-title">Compania</h4>
	</div>
</div>

<!-- ACTION BUTTONS -->
<div class="row">

	@include('includes/errors')
	@include('includes/success')
	
	<div class="col-sm-12 m-b-10">
		<div class="form-inline">
			<a href="/dist/rubro/nuevo" class="btn btn-success m-b-5"> <i class="glyphicon glyphicon-file m-r-5"></i> <span>Crear Nuevo</span></a>

			<div class="input-group">
				<input type="text" id="search" name="search" class="form-control" placeholder="Buscar...">
				<span class="input-group-btn">
				<button type="button" id="searchButton" name="searchButton" class="btn waves-effect waves-light btn-warning"><i class="fa fa-search"></i></button>
				</span>
			</div>
			
		</div>
	</div>

</div>
<!-- ACTION BUTTONS -->

<div class="row">
	<div class="col-sm-12">
		<div class="card-box">   
				
				<div class="row">
					<div class="col-sm-12 col-xs-12">
						<table class="table table-striped table-responsive" id="rubros">
							<thead>
								<tr>
									<th class="btn-success"></th>
									<th class="btn-success">Nombre</th>
									<th class="btn-success">Estatus</th>
									<th class="btn-success"><i class="fa fa-ellipsis-h"></i></th>
								</tr>
							</thead>
							<tbody>
								<tr class="gradeX">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
		</div>
	</div>
                            
</div>

@endsection



