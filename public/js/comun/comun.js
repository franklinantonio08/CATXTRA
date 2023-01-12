let objComun;

class Comun {

  constructor() {
    
  }

  init(){
  	this.globalvalues();
  	this.mascarasinput();
  }

  globalvalues(){
  	this.lengthMenuDataTable = [[5,10, 50, 100,250,500,1000,5000], [5,10, 50, 100,250,500,1000,5000]];
  	this.paginaActualReporte = 1;
  	this.lengthActualReporte = 10;
  	this.orderDirReporte = 'ASC';
  	this.orderColumnReporte = 'id';
  }

  mascarasinput(){
      $(".maskLetraNumeros").inputmask('Regex', { regex: "[a-za-zA-Z0-9áéíóúÁÉÍÓÚÑñ]+" });
      $(".maskLetraNumerosEspacio").inputmask('Regex', { regex: "[a-zA-Z0-9áéíóúÁÉÍÓÚÑñ ]+" });
      $(".maskCodigoDataBase").inputmask('Regex', { regex: "[a-zA-Z0-9\-]{0,20}" });
      $(".maskEnteros").inputmask('Regex', { regex: "[1-9]{1,1}[0-9]{0,9}" });
      $(".maskEnterosZero").inputmask('Regex', { regex: "[0-9]{1,1}[0-9]+" });
      $(".maskEmail").inputmask('Regex', { regex: "[a-zA-Z0-9\_\.\-]+@[a-za-zA-Z0-9\_\.\-]+" });
      $(".maskTelefono").inputmask('Regex', { regex: "[+]{0,1}[0-9\-() ]{0,25}" });
      $(".maskUsuario").inputmask('Regex', { regex: "[a-zA-Z0-9]+" });
      $(".maskPassword").inputmask('Regex', { regex: "[a-zA-Z0-9$%&#@?=()*\_\.\-]{0,25}" });
      $(".maskVersion").inputmask('Regex', { regex: "[0-9\.]+" });
      $(".maskCantidadUsuarios").inputmask('Regex', { regex: "[1-9]{1,1}[0-9]{0,2}" });
      $(".maskDominios").inputmask('Regex', { regex: "[/a-zA-Z0-9\-\.\_]{0,120}" });
      $(".numeroPositivoFlotante").inputmask('Regex', { regex: "^[0-9]{1,7}(\\.\\d{1,2})?$" });

      $(".maskABCEspacio").inputmask('Regex', { regex: "[a-zA-Z0-9áéíóúÁÉÍÓÚÑñ $%&#@?=()*\_\.\-]+" });
      $(".cantidadvecescontrato").inputmask('Regex', { regex: "[1-9]{1,1}[0-9]{0,3}" });

  }

  twoDecimal(value){
    var value = Math.round(value * 100) / 100;
    return value;
  }

  fourDecimal(value){
    var value = Math.round(value * 10000) / 10000;
    return value;
  }

}

$(document).ready(function(){
	objComun = new Comun();
	objComun.init();
  //console.log('comun init');
});


