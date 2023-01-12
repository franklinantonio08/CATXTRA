class MessagebasicModal {
	  constructor(modalTitle,modalMessage) {
	   	this.modalTitle = modalTitle;
	   	this.modalMessage = modalMessage;
	  }

	  init(){
	  
	  	this.acciones();

		$('#modalTitle').text(this.modalTitle);
		$('#modalMessage').text(this.modalMessage);

		$('#messagebasicModal').modal('show');

		$( "#cerrarModal" ).click(function() {
			$('#messagebasicModal').modal('hide');
		});

		$( "#closeModal" ).click(function() {
			$('#messagebasicModal').modal('hide');
		});

	  }

	  acciones(){

	  	$("#messagebasicModal").on('hide.bs.modal', function () {
       		$("#modalExtraInfoDiv").empty();
		});

	  }
}
