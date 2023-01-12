class Confirmacionmodal {
	  constructor(message, opciones, functionCallback) {
	   	this.opciones = opciones;
	   	this.message = message;
	   	this.functionCallback = functionCallback;
	  }

	  init(){
	  	const _this = this;
	  	$( "#confirmacionModalSi" ).off('click');
	  	$( "#confirmacionModalSi" ).click(function() {
			_this.functionCallback(true,_this.opciones);
			$('#confirmacionModal').modal('hide');
		});	
	  	
		$('#mensajeConfirmacion').empty().append(this.message);
	  	$('#confirmacionModal').modal('show');

	  	$( "#confirmacionModalNo" ).click(function() {
			$('#confirmacionModal').modal('hide');
		});

		$( "#cerrarModal" ).click(function() {
			$('#confirmacionModal').modal('hide');
		});


	  }
}