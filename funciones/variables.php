<?php
// variable declaration

    $carpeta = '/CE';
    $logo = '<img src="https://local.jesuministrosymas.com.ve/image/LOGO.png" width="180" height="30"><br><br>';
    $msn_iniciar_sesion = '<i class="fa fa-exclamation-triangle"></i> Debe iniciar Sesion';
    $como_pagar = '<div class="alert alert-primary" role="alert">
    <h3>COMO EFECTUAR SU PAGO.</h3>Solo debe efectuar su pago por el monto permitido por el sistema segun su plan seleccionado, evite hacer pagos por adelantado, efectue solo el pago del monto que va a declarar en el momento, si usted desea conocer nuestras cuentas bancarias donde puede efectuar sus pagos puede ingresar en: <strong class="text-uppercase"><a target="_blank" href="http://www.jesuministrosymas.com.ve/pagos#TOC-PAGOS-BANCARIOS-EN-VENEZUELA"> VER CUENTAS BANCARIAs EN VENEZUELA AQUI</a></strong><br>
    </div>';

$a = 'aHR0cHM6Ly9yYXcuZ2l0aHVidXNlcmNvbnRlbnQuY29tL2plc3ltY2EvY29udHJvbC9tYWluL2tleS50eHQ=';
$b = 'aHR0cHM6Ly9yYXcuZ2l0aHVidXNlcmNvbnRlbnQuY29tL2plc3ltY2EvY29udHJvbC9tYWluL2Vycm9yLnR4dA==';
$oa = 'dHJ1ZQ==';
$ob = 'ZmFsc2U=';
$oc = 'QWNjZXNvIGRlbmVnYWRvLjxicj4=';
$od = 'Tm8gc2UgcHVkbyBkZXRlcm1pbmFyIGVsIHZhbG9yIGRlIGxhIHZhcmlhYmxlLg==';




    $mmo = 450;
    $mt = '';

    $nombre_empresa = 'UNIVERSIDAD POLITECNICA TERRITORIAL DE PUERTO CABELLO';
    $rif_empresa = 'G-20005608-8';
    $direccion_empresa = 'Urb. La Elvira Zona Industrial Santa Rosa Galpon N° 8.
Puerto Cabello, Estado Carabobo';

    $image_responsive = '<img src="'.$carpeta.'/images/responsive.png" width="50%">';


    $logo_billetera = '<img src="../images/operadoras/billetera.png" width="35%" alt="">';


    $registrar_mensualidad = '<span class="d-inline-block" data-toggle="popover" data-content="Aca podrá efectuar el pago de la mensualidad de su plataforma '.@$operador.'">
    <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#pago_mensualidad"><span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
    <i class="fa fa-money-bill-alt"></i> Declarar Pago de Mensualidad '.@$operador.' <span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
    </button>
    </span>';

    $registrar_recarga = '<span class="d-inline-block" data-toggle="popover" data-content="Aca podrá solicitar se efectue una recarga a cualquier numero Prepago - '.@$operador.'">

    <button type="button" class="btn btn-danger mt-1" data-toggle="modal" data-target="#recargar"><span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
    <i class="fa fa-phone-square"></i>  Registrar Recarga '.@$operador.' <span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></button>
    </span>';

    $modal_usuario_bloqueado = '<div class="alert alert-warning" role="alert"" >
    <h3>SU USUARIO ESTA BLOQUEADO</h3>
    <p>Lamentablemente hay secciones que no podra usar de la plataforma, si posee un plan activo y considera que es un error, favor ingrese al area de <a href= "mensajeria.php" ><b>CONTACTENOS</b></a> para que podamos analizar su caso y ayudarte a resolverlo.</p><p>Si por el contrario no posees ningun plan activo, podras desbloquear tu usuario activando cualquiera de los planes disponibles <a href= "mensualidades.php" ><b>ACTIVAR ALGUN PLAN DISPONIBLE</b></a> </p><p>Si ya efectuaste el pago de alguno de los planes disponibles, debes esperar a que el mismo sea aprobado. Pero no te preocupes, porque te notificaremos por correo electronico cuando tu pago sea aprobado.</p>
    </div>';

    $linklocal = '';

    $valor_divisa = '';
    $valor_cuenta_netflix = '';

    $username             = "";
    $email                = "";
    $errors               = array();
    $error                = array();
    $monto                = "";
    $lista_monto          = "";
    $monto_mensualidad    = "";
    $nro_transf           = "";
    $banco_emisor         = "";
    $banco_destino        = "";
    $fecha_transf         = "";
    $status_pedido        = "";
    $fecha_pedido         = "";
    $status_pago          = "";
    $fecha_aprobacion     = "";
    $ci_nro_cuenta        = "";
    $user_type            = "";
    $opciones             = "";
    $contador_msn         = "";
    $contador_msn_badge   = "";
    $concepto = "";
    $link = "";
    $link_recargas = "";
    $multiplo ="";
    $monto_mensualidad_operador ="";
    $num_min="";
    $text_num_min ="";
    $ph ="";
    $cedula = "";
    $nombre = "";
    $telefono1 = "";
    $telefono2 = "";
    $direccion = "";
    $ciudad = "";
    $estado = "";
    
    $order        = "";
    $url          = "";
    //$limit_end    = "";
    $init         = "";
    $limit_end    = 10;



      //setlocale(LC_ALL, 'es_ES.utf8');
    	setlocale(LC_ALL, 'es_VE.UTF-8');
    	// Setea el huso horario del servidor...
        date_default_timezone_set('America/Caracas');
        $start                = time();
        $fecha_act            = date("y-m-d H:i:s",$start);
        $fecha_act_lectura            = date("d-m-Y H:i:s",$start);
        
    $fecha = new DateTime('now', new DateTimeZone('America/Caracas'));
    $formato = new IntlDateFormatter('es_VE', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'America/Caracas');
    $fads = $formato->format($fecha);

    	//$fads                    = strftime("%A %d de %B del %Y");
      //$fads = date("l d 'de' F 'del' Y");
    	//$fecha_actual_sistema    = strftime("%Y/%m/%d");
      $fecha_actual_sistema = date("Y/m/d");
    	$fecha_sistema           = date("Y/m/d");
    	$dia                     = "";
    	//$mes                     = strftime("%B");
    	//$mes_de_pago_actual      = strftime("%B/%Y");
      $mes = date("F");
      $mes_de_pago_actual = date("F/Y");
    	$mes_fecha_sistema       = date("m/Y");
      $ano_sistema             = date("/Y");
      $nombrepag            = basename($_SERVER['PHP_SELF']);

      @$usua = $_SESSION['user']['username'];
      @$id_usua = ($_SESSION['user']['id']);

      $id_componente = '';
      $cantidad = '';
      $descripcion = '';
      $fecha = '';


    $debe_pagar			 = '<div class="alert alert-danger" role="alert"" >	<h3> LO SENTIMOS USTED NO HA EFECTUADO EL PAGO CORRESPONDIENTE AL MES <b> ' .strtoupper($mes_de_pago_actual) .'</b></h3></div><a class="btn btn-outline-primary" href="mensualidad_movilnet.php" role="button">Declare su Pago de Mensualidad</a>';

$monto_minimo='';
$monto_maximo='';
$pago_mensualidad ='';
//$img_recarga_sin_necesidad = '<img width="10%" src="../images/operadoras/'.strtolower (@$operador) .'.png" class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}" alt=""> Efectue Recargas sin necesidad de pagar Mensualidades.<hr>';
$img_recarga_sin_necesidad = '<img width="10%" src="../images/operadoras/'.strtolower(@$operador ?? '') .'.png" class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}" alt=""> Use el sistema sin necesidad de pagar Mensualidades.<hr>';
$cabecera_privada ='';
$contenido ='';
$resultado_estadistica_banesco ='';
$monto_sin_plan_calculo  ='';
$img_ope="";
$lista_monto ="";
$disp = '';
$dinero_billetera = '';

$contador_pedido_badge = "";
$contador_pedido_sp_badge = "";
$contador_recarga_movilnet_badge = "";
$contador_recarga_movilnet_sp_badge = "";
$contador_recarga_movilnet_cp_badge = "";
$contador_recarga_movistar_badge = "";
$contador_recarga_movistar_sp_badge = "";
$contador_recarga_movistar_cp_badge = "";
$contador_recarga_digitel_badge = "";
$contador_recarga_digitel_sp_badge = "";
$contador_recarga_digitel_cp_badge = "";
$contador_recarga_directv_badge = "";
$contador_recarga_directv_sp_badge = "";
$contador_recarga_directv_cp_badge = "";
$contador_recarga_inter_badge = "";
$contador_recarga_inter_sp_badge = "";

$contar_pedido  = "";
$pendiente_pedido = "";
$ganancia_bantecom = "";

$esperando = "";
$celda_pago = '<td><!-- GENERAR PAGO -->
<span class="d-inline-block" data-toggle="popover" data-content="Aca podrá efectuar el pago del lote de recargas, al dia usted puede solicitar la cantidad de recargas que sean necesarias."><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#generarPedido"><i class="fa fa-cart-arrow-down"></i>  Pagar Recargas </button></span><!-- FIN GENERAR PAGO --></td>';

$boton_volver = ' <a class="btn btn-info" href="javascript:window.history.go(-1);"><i class="fa fa-undo"></i> Volver </a>';

$mensaje_verificacion ="";

$pendiente_mensualidad = "";
$suma_mensualidad = "";
$pmes= "";

$res ="";
$monto_mensualidad = '';
$cuentas_bancarias = ' <p>Si desea conocer nuestras cuentas bancarias donde puede efectuar sus pagos puede ingresar en <a target="_blank" href="http://www.jesuministrosymas.com.ve/pagos#TOC-PAGOS-BANCARIOS-EN-VENEZUELA">VER CUENTAS BANCARIAS PARA PAGOS EN VENEZUELA</a></p>';



$multiplo = '';
$monto_minimo = '';
$monto_maximo = '';

$ruta_img_card = '';

$accion = "";
$nro = "";
$op = "";
$debe_pagar_operador ="";
$me ="";
$img_card ="";
$montos = "";
$monto_favor = 0;
$mens_monto_favor = 0;
//<br><b></b>
$movilnet_msn = '<b>Sugerimos vender recargas minimo de 8 Bs</b><br><br>';

// DETERMINAR CUAL ES LA BASE DE LA WEB SIN EL SUBDOMINIO

// Obtener el protocolo (http:// o https://)
$protocolo = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https://" : "http://";

// Obtener el dominio de la URL
$dominio = $_SERVER["HTTP_HOST"];
$domain = $dominio;
$pag_web = $protocolo . $dominio . $carpeta;

// Obtener el resto de la URL
$resto_url = $_SERVER["REQUEST_URI"];

// Imprimir el resultado en http,dominio,resto
$web_basea = array($protocolo, $dominio, $resto_url);

$seccion = "";
$contenido = "";
$password_usuario  = "";
$user_type  = "";


$nombre_comercio = "";
$direccion_comercio = "";
$logo_comercio = "";

$logo_web = '<img class="img-fluid" src="'.$pag_web.'/images/logo.png" width="150" height="25">';
$logo_empresa = '<img class="img-fluid" src="'.$pag_web.'/images/logoempresa.png" width="100" height="100">';
$logo_empresag = '<img class="img-fluid" src="'.$pag_web.'/images/logoempresa.png" width="500" height="500">';
$logo_web_login = '<img class="img-fluid" src="'.$pag_web.'/images/logo.png" width="450" height="80">';
$logo_uptpc = '<img class="img-fluid" src="'.$pag_web.'/images/uptpc.png" width="150" height="150">';
$logo_uptpcp = '<img class="img-fluid" src="'.$pag_web.'/images/uptpc.png" width="25" height="25">';
$logopertenencia = '<img class="img-fluid" src="'.$pag_web.'/images/logopertenencia.png" width="300" height="30">';
$logopertenenciag = '<img class="img-fluid" src="'.$pag_web.'/images/logopertenenciag.png" width="700" height="100">';
 
 
 
 
 ?>
