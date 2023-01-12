var secundariaAddModal = [];

class Secundariamodal {
	  constructor(opciones, functionCallback) {
	   	this.opciones = opciones;
	   	this.functionCallback = functionCallback;
	   	this.dataTable = '';
	  }

	  init(){
	  	const _this = this;

			console.log("por aqui vamos");



	  		$( "#searchSingleSecundariaModal" ).off('click');
			$( "#searchSingleSecundariaModal" ).click(function() {
				_this.secundaria();
			});

			$('#inputSingleSecundariaModal').keyup(function(event){
				_this.secundaria();
			});

	  	this.secundaria();		
	  	$('#secundariaListModal').modal('show');

		 // console.log('por aqui va');

		 $( "#cerrarModalSecundaria" ).click(function() {
			$('#secundariaListModal').modal('hide');
		});

		 $( "#cerrarModalsecundaria" ).click(function() {
			$('#secundariaListModal').modal('hide');
		});

		
		
	  }

	  /*BEGIN TABLA USUARIO*/
		secundaria(){

			var BASEURL = window.location.origin; 

			//console.log(BASEURL);

            const generalesCodigo = document.getElementById('generalesCodigo').value;

            //console.log(generalesCodigo);

			//console.log("por aqui vamos");

			$("#secundariaTableModal > tbody").empty();	

				var _this = this;

				var search = $( '#inputSingleSecundariaModal' ).val();

				var table = $('#secundariaTableModal').DataTable( {
					"destroy": true,
					"searching": false,
					"serverSide": true,
					"info": true,
					"lengthMenu": objComun.lengthMenuDataTable,
					"pageLength": pageLengthDataTable, //Variable global en el layout
					"language": {
						"lengthMenu": "Mostrar _MENU_ por página",
						"zeroRecords": "No se ha encontrado información",
						"info": "Mostrando _PAGE_ de _PAGES_",
						"infoEmpty": "",
					},
					"ajax": {
						"url":BASEURL+"/dist/secundaria/listadoagregarsecundaria",
						//"url":BASEURL+'dist/secundaria/listadoagregarsecundaria',
						"type": "POST",
						"data": function ( d ) {
							var info = $('#secundariaTableModal').DataTable().page.info();
							d.currentPage = info.page + 1;
							d.searchInput = search;
							d._token=token;
							d._generalesCodigo=generalesCodigo;
						}
					},
					"columns": [
						{ "data": "nombre" },
						{ "data": "codigo" },
						{ "data": "detalle" , "orderable": false, className: "actions text-right"},
					],
					"initComplete": function (settings, json) {
						 
					},
					"infoCallback": function( settings, start, end, max, total, pre ) {
						_this.agregarSecundaria();
						var api = this.api();
						var pageInfo = api.page.info();
						return 'Mostrando '+ (pageInfo.page+1) +' de '+ pageInfo.pages;
					}
				});

		}
		/*END TABLA USUARIO*/

		agregarSecundaria(){
			console.log("por aqui camos agregar secundaria");

			var _this = this;
			$( ".agregarSecundariaModal" ).off('click');
			$( ".agregarSecundariaModal" ).click(function() {

				

				$( '#inputSingleSecundariaModal' ).val('');

				const secundariaId = $( this ).attr( "attr-id" );
				const secundariaNombre = $( this ).attr( "attr-nombre" );
				//const distribuidorId = $( this ).attr( "attr-distribuidorId" );
				//const secundariaEmail = $( this ).attr( "attr-email" );
				const secundariaCodigo = $( this ).attr( "attr-codigo" );
		
				var secundariaInfo = {secundariaId:secundariaId,secundariaNombre:secundariaNombre,secundariaCodigo:secundariaCodigo};

				$('#secundariaListModal').modal('hide');


				_this.functionCallback(true,secundariaInfo,_this.opciones);
			});

		}
}