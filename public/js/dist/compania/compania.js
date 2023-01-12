class Distcompania {
    constructor() {
    }

    init(){
        
        if($('#compania').length) {
          this.compania('');
      }

      if($('#nuevoregistro').length) {
          this.validatecompania();
      }
     
      this.acciones();

    }

    acciones(){

        const _this = this;
                
        $( "#searchButton" ).off('click');
        $( "#searchButton" ).click(function() {
          _this.compania( $( "#search" ).val() );
      });

      $('#search').keypress(function(event){
          var keycode = (event.keyCode ? event.keyCode : event.which);
          if(keycode == '13'){
              _this.compania( $( "#search" ).val());
              event.preventDefault();
              return false;
          }
          event.stopPropagation();
      });
    
    }

        /*BEGIN TABLA USUARIO*/
        compania(search){

            //var BASEURL = window.location.origin; 

            //console.log(BASEURL);

          const _this = this

              const table = $('#compania').DataTable( {
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
                      "url":BASEURL,
                      "type": "POST",
                      "error": this.handleAjaxError, 
                      "data": function ( d ) {
                          var info = $('#compania').DataTable().page.info();
                          
                          var orderColumnNumber = d.order[0].column;
                          
                          objComun.orderDirReporte = d.order[0].dir; //Variable global en comun.js
                          objComun.orderColumnReporte = d.columns[orderColumnNumber].data; //Variable global en comun.js
                          objComun.lengthActualReporte = info.length; //Variable global en comun.js
                          objComun.paginaActualReporte = info.page+1; //Variable global en comun.js
                          
                          d.currentPage = info.page + 1;
                          d.searchInput = search;
                          d._token=token;
                      }
                  },
                  "columns": [
                      { "data": "id"},
                      { "data": "nombre" },
                      { "data": "estatus" },
                      { "data": "detalle" , "orderable": false, className: "actions text-right"},
                  ],
                  "initComplete": function (settings, json) {

                  },
                  "infoCallback": function( settings, start, end, max, total, pre ) {

                      _this.desactivarcompania();

                      var api = this.api();
                      var pageInfo = api.page.info();
                      return 'Mostrando '+ (pageInfo.page+1) +' de '+ pageInfo.pages;
                  }
              });
  
          }

          handleAjaxError( xhr, textStatus, error ) {
              console.log(error);
          }

          /*END TABLA USUARIO*/

          /*BEGIN VALIDAR NUEVO USUARIO*/
          validatecompania(){

              $("#nuevoregistro").validate({
                  submitHandler: function(form) {
                      console.log('submit');
                      form.submit();
                   },
                   invalidHandler:function(form) {
                      
                   },
                   highlight: function(element) {
                       var titleElemnt = $( element ).attr( "id" );
                       $("button[data-id*='"+titleElemnt+"']").addClass( "errorValidate" );
                       $(element).addClass( "errorValidate" );
                    },
                   unhighlight: function(element) {
                       var titleElemnt = $( element ).attr( "id" );
                       $("button[data-id*='"+titleElemnt+"']").addClass( "successValidate" );
                       $(element).addClass( "successValidate" ); 
                    },
                  rules: {
                      nombre: {
                          required: true,
                      }
                  },
                  messages: {
                      nombre: {
                          required: "",
                      }
                  }
              });
          }
          /*END VALIDAR NUEVO USUARIO*/


          /*BEGIN DESACTIVAR UN USUARIO*/
          desactivarcompania(){

              const _this = this

              $( ".desactivar" ).off('click');
                $( ".desactivar" ).click(function() {
                    
                    const companiaId = $( this ).attr( "attr-id" );
                    var opciones = {companiaId:companiaId};
                    const message = 'Seguro que desea cambiar de estatus el compania?'
                    const objConfirmacionmodal = new Confirmacionmodal(message, opciones, _this.callbackDesactivarCompania);
                  objConfirmacionmodal.init();


                  
              });
          }
          

          callbackDesactivarCompania(response, opciones){

              if(response == true){

                  const _this = this;

                  $.post( BASEURL+'/desactivar', 
                  {
                      companiaId: opciones.companiaId,
                      _token:token 
                      }
                  )
                  .done(function( data ) {

                      if(data.response == true){
                          const modalTitle = 'Compania';
                          const modalMessage = 'El compania ha sido cambiado de estatus';
                          const objMessagebasicModal = new MessagebasicModal(modalTitle, modalMessage);
                          objMessagebasicModal.init();

                          const objDistcompania = new Distcompania();							
                          objDistcompania.compania($( "#search" ).val());

                      }else{
                          
                          const modalTitle = 'Compania';
                          const modalMessage = 'El compania no se ha podido cambiar de estatus';
                          const objMessagebasicModal = new MessagebasicModal(modalTitle, modalMessage);
                          objMessagebasicModal.init();

                          const objDistcompania = new Distcompania();							
                          objDistcompania.compania($( "#search" ).val());
                      }
                  })
                  .fail(function() {
                      
                      const modalTitle = 'Compania';
                      const modalMessage = 'El compania no se ha podido cambiar de estatus';
                      const objMessagebasicModal = new MessagebasicModal(modalTitle, modalMessage);
                      objMessagebasicModal.init();

                      const objDistcompania = new Distcompania();							
                      objDistcompania.compania($( "#search" ).val());
                      
                  })
                  .always(function() {
                      
                  }, "json");

              }
              
          }

          /*END DESACTIVAR UN DISTRIBUIDOR*/



  }


$(document).ready(function(){

  const objDistcompania = new Distcompania();
  objDistcompania.init();

});