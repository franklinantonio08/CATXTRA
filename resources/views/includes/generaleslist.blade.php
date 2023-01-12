<div class="modal fade bs-example-modal-lg" id="generalesListModal" tabindex="-1" role="dialog" aria-labelledby="messageTitle">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
		
		<div class="modal-header">
			<h4 class="modal-title">Lista de generales</h4>
			<button type="button"  id='cerrarModalGenerales' class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		</div>


			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6 m-b-10">
						<div class="form-inline">
							<div class="input-group">
								<input type="text" id="inputSingleGeneralesModal" name="inputSingleGeneralesModal" autocomplete="off" class="form-control" placeholder="Buscar...">
								<span class="input-group-btn">
								<button type="button" id="searchSingleGeneralesModal" name="searchSingleGeneralesModal" class="btn waves-effect waves-light btn-warning"><i class="fa fa-search"></i></button>
								</span>
							</div>   
						</div>
					</div>

					<div class="row">
						<div class="table-responsive">	
							<table class="table table-bordered data-table" id="generalesTableModal" style='width:100%'>
							<thead>
								<tr>
								<th class="bg-primary fs-8 fw-semibold text-white">Nombre</th>
								<th class="bg-primary fs-8 fw-semibold text-white">C&oacute;digo</th>
								<th class="bg-primary fs-8 fw-semibold text-white"></th>
								</tr>
							</thead>
							
							<tbody>
							</tbody>

							</table>
						</div>
					</div>
				</div>
			</div>

      <div class="modal-footer">
        <button type="button" id='cerrarModalgenerales' class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
