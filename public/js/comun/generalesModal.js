var generalesAddModal = [];

class Generalesmodal {
	  constructor(opciones, functionCallback) {
	   	this.opciones = opciones;
	   	this.functionCallback = functionCallback;
	   	this.dataTable = '';
	  }

	  init(){
	  	const _this = this;

			console.log("por aqui vamos");

	  		$( "#searchSingleGeneralesModal" ).off('click');
			$( "#searchSingleGeneralesModal" ).click(function() {
				_this.generales();
			});

			$('#inputSingleGeneralesModal').keyup(function(event){
				_this.generales();
			});

	  	this.generales();		
	  	$('#generalesListModal').modal('show');

		 // console.log('por aqui va');

		 $( "#cerrarModalGenerales" ).click(function() {
			$('#generalesListModal').modal('hide');
		});

		 $( "#cerrarModalgenerales" ).click(function() {
			$('#generalesListModal').modal('hide');
		});

		
		
	  }

	  /*BEGIN TABLA USUARIO*/
		generales(){

			var BASEURL = window.location.origin; 

			console.log(BASEURL);

			console.log("por aqui vamos");

			$("#generalesTableModal > tbody").empty();	

				var _this = this;

				var search = $( '#inputSingleGeneralesModal' ).val();

				var table = $('#generalesTableModal').DataTable( {
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
						"url":BASEURL+"/dist/generales/listadoagregargenerales",
						//"url":BASEURL+'dist/generales/listadoagregargenerales',
						"type": "POST",
						"data": function ( d ) {
							var info = $('#generalesTableModal').DataTable().page.info();
							d.currentPage = info.page + 1;
							d.searchInput = search;
							d._token=token;
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
						_this.agregarGenerales();
						var api = this.api();
						var pageInfo = api.page.info();
						return 'Mostrando '+ (pageInfo.page+1) +' de '+ pageInfo.pages;
					}
				});

		}
		/*END TABLA USUARIO*/

		agregarGenerales(){
			console.log("por aqui camos agregar generales");

			var _this = this;
			$( ".agregarGeneralesModal" ).off('click');
			$( ".agregarGeneralesModal" ).click(function() {

				

				$( '#inputSingleGeneralesModal' ).val('');

				const generalesId = $( this ).attr( "attr-id" );
				const generalesNombre = $( this ).attr( "attr-nombre" );
				//const distribuidorId = $( this ).attr( "attr-distribuidorId" );
				//const generalesEmail = $( this ).attr( "attr-email" );
				const generalesCodigo = $( this ).attr( "attr-codigo" );
		
				var generalesInfo = {generalesId:generalesId,generalesNombre:generalesNombre,generalesCodigo:generalesCodigo};

				$('#generalesListModal').modal('hide');


				_this.functionCallback(true,generalesInfo,_this.opciones);
			});

		}
}