(function () {

var fechaR1='';
var fechaR2='';
//fecha que aparecera por defecto (la actual)
    window.onload = function() {
        let fecha = new Date();
        let mes = fecha.getMonth() + 1;
        let dia = fecha.getDate();
        let anio = fecha.getFullYear();
        if (dia < 10)
            dia = '0' + dia;
        if (mes < 10)
            mes = '0' + mes
       $('#fecha2').val(anio + "-" + mes + "-" + dia);
      $('#fecha1').val(anio + "-" + mes + "-" + dia);
      fechaR1=anio + "-" + mes + "-" + dia;
      fechaR2=anio + "-" + mes + "-" + dia;
      consult(0,1,fechaR1,fechaR2);
    }

//funciones..




//EVENTOS..............


/*================================*/
/*========== CONSULTAS ===========*/
/*================================*/  
var direccion=$('#contenedor')[0].getAttribute('url');//direcion de la consulta

$(consult('5','1','1','', direccion));

function consult(mos,txt,fecha1,fecha2, url) {
 
  $.ajax({ 

       type: "POST",
       url:url,
       dataType:"html",
        data: {
        mos:mos,
        fecha1:fecha1,
        fecha2:fecha2

       },
       async:false


  }).done(function(rd) {

    if ($("#contenido_tabla").length>0) {//para que pararesca en las tablas

       $('#contenido_tabla').html(rd);
    }else{
      $('#contenedor').html(rd);//para que pararesca en un contenedor
    }

     
     //console.log(rd);
    
  });
}

if ($('#mos').length > 0) {

  $('#mos').change(function() {
  var mos = this.value;
  var txt = $('#buscar').val();
  
  consult(mos, txt, direccion);
});

}


//buscador 1

if ($('#buscar').length > 0) {
  $(document).on('keyup', '#buscar',function(){

  var mos = $('#mos').val();
  var txt = this.value;

  consult(mos, txt, direccion);

});
}

//buscador 2

if ($('#busLibro').length > 0) {

  $(document).on('submit', '#busLibro',function(event){
event.preventDefault();
  var mos = 10000000;
  var txt = $('#text').val();

  consult(mos, txt, direccion);

});
}



//buscador 3

if ($('#fecha1').length > 0) {

  $(document).on('change', '#fecha1',function(){

  var mos = $('#mos').val();
  var fecha1 = this.value;
  var fecha2 = $('#fecha2').val();
  consult(mos,'1',fecha1,fecha2,direccion);

});

 $(document).on('change', '#fecha2',function(){

  var mos = $('#mos').val();
  var fecha1 = $('#fecha1').val();
  var fecha2 = this.value;

  consult(mos,'1',fecha1,fecha2,direccion);

});


}



console.log('direcion de la consulta',direccion);


})();