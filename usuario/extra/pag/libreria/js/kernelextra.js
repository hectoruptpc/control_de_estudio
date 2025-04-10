instalado = [];
function importar_modulos() {
    for (var i = 1; i < 9; i++) {
  document.write("<script type='text/javascript' src='extra/modulos/"+i+"/libreria/js/kernelmodulo.js'></script>");    
}

}
importar_modulos();

function extension() {
    ifrm = "<div class=\"panel panel-info\">";
    ifrm += "<div class=\"panel-heading\"><strong>Seccion de Extensiones</strong><\/div>";
    ifrm += "<div class=\"panel-body\">";
    ifrm += "<div class='alert alert-info' role='alert'> <strong>Todo ha Salido Correctamente!</strong> Usted Ha instalado de manera correcta esta seccion de Extensiones.</div>";
    
    ifrm += "<h2>Modulos Instalados:</h2>";
  for (n in instalado){
    ifrm += instalado[n];
  }
    ifrm += "</div></div>";
    document.getElementById("contenido").innerHTML = ifrm;
}

function mensextra(){
    document.getElementById('mensextra').innerHTML = "<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><span class='glyphicon glyphicon-alert' aria-hidden='true'> </span> <strong> Se ha Detectado una Extension del Curso Instalada! </strong> <a data-dismiss='alert' aria-label='Close' class='clase6' onclick='extra();' title='Extencion Detectada' href='#extra'>INGRESE A SUS EXTENSIONES AQUI</a></div>";
}

