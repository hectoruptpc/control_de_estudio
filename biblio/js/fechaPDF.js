(function () {
	
const url = $("a[target$='_blank']")[0].getAttribute("href");//dirección el pdf a crear

$("a[target$='_blank']").click(function(){

		let fecha = new Date();
        let mes = fecha.getMonth() + 1;
        let dia = fecha.getDate();
        let anio = fecha.getFullYear();
        if (dia < 10)
            dia = '0' + dia;
        if (mes < 10)
            mes = '0' + mes

var fechaA = anio + "-" + mes + "-" + dia;//fecha actual

//fechas de los reportes
var fecha1 = anio + "-" + mes + "-" + dia;
var fecha2 = anio + "-" + mes + "-" + dia;

if($('#fecha2')){

fecha2 = $('#fecha2').val();
}



if($('#fecha1')){

fecha1 = $('#fecha1').val();

}


var acto =$("#acto option:selected").text();
var actoV =$("#acto").val();

var titulo =$("#titulo option:selected").text();
var tituloV =$("#titulo").val();

var newUrl = url+'?fecha1='+fecha1+'&fecha2='+fecha2+'&carrCode='+$("#carr").val()+'&carrT='+$("#carr option:selected").text()+'&contador='+$('#cant').text()+'&acto='+acto+'&actoV='+actoV+'&titulo='+titulo+'&tituloV='+tituloV;


this.setAttribute("href", newUrl);

//alert(newUrl);

});




})();