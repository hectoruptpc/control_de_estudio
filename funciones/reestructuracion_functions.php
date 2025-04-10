<?php
//PARA EL MODAL DE PAGO DE MENSUALIDAD SIN FORMATO MOVILNET
function pago_mensualidad_operador(){
    global $db, $username, $usua, $ci_nro_cuenta, $monto_mensualidad, $nro_transf, $banco_emisor, $banco_destino, $fecha_transf, $status_pedido, $fecha_pedido, $status_pago, $fecha_aprobacion,$mes_de_pago_actual, $debe_pagar, $mmo, $concepto, $operador, $link, $m_dias_r, $fecha_sistema, $mens_monto_favor, $monto_favor;

    selector_operador();
    monto_mensualidad_operador();

$mcf = number_format($mmo, 2, ',', '.');
    $queryvpm = "SELECT *, DATEDIFF(fin, '$fecha_sistema') as DiasRestantes FROM pagos WHERE user = '$usua' AND concepto = '$concepto' ORDER BY id DESC LIMIT 1 ";
	$resultvpm = mysqli_query($db, $queryvpm);
	$rowsvpm =  mysqli_num_rows($resultvpm);
    $rowsvpma =  mysqli_fetch_assoc($resultvpm);

    analisis_dias_restantes();


    // if (isActive()){

      if ($rowsvpma['DiasRestantes'] >0)
      {

        //if ($rowsvpm > 0){
            if ($rowsvpma['status_pago'] == 'PENDIENTE'){
                echo '<div class="alert alert-danger" role="alert"" >
            <h3>YA USTED EFECTUO EL PAGO DEL PERIODO <b>'
        .strtoupper($mes_de_pago_actual) .'</b> PARA LAS RECARGAS '.strtoupper($operador).' Y EL STATUS DE DICHO PAGO ES <b>'.$rowsvpma['status_pago'].'</b> DEBE ESPERAR QUE SU PAGO SEA APROBADO PARA QUE PUEDA ACCEDER AL MODULO DE RECARGAS <b>'.$link.'</b></h3>
        </div>';
            }
            else if ($rowsvpma['status_pago'] == 'APROBADO'){
                echo '<div class="alert alert-info" role="alert"" >
            <h3>YA USTED EFECTUO EL PAGO DEL PERIODO <b>'
        .strtoupper($mes_de_pago_actual) .'</b> PARA LAS RECARGAS '.strtoupper($operador).' Y EL STATUS DE DICHO PAGO ES <b>'.$rowsvpma['status_pago'].'</b> YA PUEDE ACCEDER AL MODULO DE RECARGAS <b>'.$link.'</b><br> '.strtoupper($m_dias_r).'</h3>
        </div>';
            }

            //}
             else {

               a_favor();
               echo $mens_monto_favor;
               $monto_favor = $GLOBALS['monto_a_favor'];


                echo ' Si desea conocer nuestras cuentas bancarias donde puede efectuar sus pagos puede ingresar en <a target="_blank" href="http://www.jesuministrosymas.com.ve/pagos#TOC-PAGOS-BANCARIOS-EN-VENEZUELA">VER CUENTAS BANCARIAS PARA PAGOS EN VENEZUELA</a>';
contenido('bancario');
    echo ' <form autocomplete="off" class="was-validated" method="post" action= "mensualidad_'.strtolower ($operador) .'.php">';
    //echo $status_usuario;



// Your date
$inicio = new DateTime(); // empty for now or pass any date string as param
//echo $inicio->format('Y-m-d');
//echo "<br>";
$hoy = date('d/m/Y');
// Adding

// or even easier
$fin = $inicio->modify('+1 month');
$fin = $fin->format('d/m/Y');

// Checking
//echo $fin->format('Y-m-d');
echo "<br>";


    echo '<div class="form-group">
    <label for="monto_mensualidad"><h5>Monto a pagar</h5>El monto a pagar para poder activar el servicio de Recargas '.$operador.' es de: <b>'. $mcf .' Bs.</b></label>


    <input type="hidden" name="monto" value="'.$mmo.'">
    <input type="hidden" name="concepto" value="'.$concepto.'">

    <input type="hidden" name="monto_favor" value="'.$monto_favor.'">

    </div>';

    echo '<div class="alert alert-warning" role="alert"><h5>Vigencia de su Plan '.$operador.'</h5>Por ejemplo:<br>Aprobandose su pago hoy: <b>'. $hoy .'</b><br>Su renta venceria el <b>'. $fin .'</b>


    <input type="hidden" name="inicio" value="'.$hoy.'">
    <input type="hidden" name="fin" value="'.$fin.'">

    </div>';

    echo '

    <div class="form-group">
    <label for="banco_emisor">Desde Que banco Transfirio</label>
    <select class="custom-select" id="banco_emisor" name="banco_emisor" value="';
    echo $banco_emisor;
    echo '" required >
    <option value="">Seleccione:</option>';
    banco_emisor();
    echo '</select> <div class="invalid-feedback">Debe Seleccionar desde que banco efectuo su transferencia.</div>
    </div>

    <div class="form-group">
    <label for="banco_destino">A que Banco Transfirio</label>
    <select class="custom-select" id="banco_destino" name="banco_destino" value="';
    echo $banco_destino;
    echo '" required >
    <option value="">Seleccione:</option>';
    banco_destino();
    echo '</select>
    <div class="invalid-feedback">Debe Seleccionar a que banco usted efectuo su transferencia.</div>
    </div>

    <div class="form-group">
    <label for="nroTransf">Numero de Transferencia</label>
    <input pattern="[0-9]{8,15}" title = "Debe utilizar solo Numeros, Minimo 8 digitos y Maximo 15 digitos. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234"  type="text" class="form-control" id="nro_transf" aria-describedby="nro_transf" placeholder="Numero de Operacion Bancaria" name="nro_transf" value="';
    echo $nro_transf;
    echo '" required>
    <div class="invalid-feedback">Debe indicar el numero de operacion bancaria indicada por su Banco. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234</div>
    </div>

    <div class="form-group">
    <label for="ci_nro_cuenta">Cedula del Titular de la Cuenta Origen</label>
    <input  pattern="[0-9]{7,10}" title = "Debe utilizar solo Numeros, Minimo 7 digitos y Maximo 10 digitos"   type="text" class="form-control" id="ci_nro_cuenta" aria-describedby="ci_nro_cuenta" placeholder="Numero de Cedula Titular de la Cuenta Origen" name="ci_nro_cuenta" value="';
    echo $ci_nro_cuenta;
    echo '" required>
    <div class="invalid-feedback">Debe indicar el numero de cedula del titular de la cuenta desde donde usted efectuo su transferencia.</div>
    </div>

    <div class="form-group">
    <label for="fechaTransf">Fecha de su Transferencia</label>
    <input pattern="(?: 30)) | (? :(? : 0 [13578] | 1 [02]) - 31)) / (? :(?: 0 [1-9] | 1 [0-2]) - (?: 0 [1-9] | 1 [0 -9] | 2 [0-9]) | (? :( ?! 02) (?: 0 [1-9] | 1 [0-2]) / (?: 19 | 20) [0-9] {2}" title = "Debe utilizar el formato DD/MM/YYYY" type="date" class="form-control" id="fecha_transf" aria-describedby="fecha_transf" placeholder="Numero de Operacion Bancaria" name ="fecha_transf" value="';
    echo $fecha_transf;
    echo '" required>
    <div class="invalid-feedback">Debe Seleccione la fecha en que usted efectuo su transferencia.</div>
    </div>

    <input type="hidden" name="user" value="'.$usua.'">
    <input type="hidden" name="titulopag" value="'.$operador.'">

    <button type="submit" class="btn btn-primary" name="pago_mensualidad_operadoras_btn">Enviar</button>

    </form>';
    }
    }
    else {

      a_favor();
      echo $mens_monto_favor;
      $monto_favor = $GLOBALS['monto_a_favor'];


       echo ' Si desea conocer nuestras cuentas bancarias donde puede efectuar sus pagos puede ingresar en <a target="_blank" href="http://www.jesuministrosymas.com.ve/pagos#TOC-PAGOS-BANCARIOS-EN-VENEZUELA">VER CUENTAS BANCARIAS PARA PAGOS EN VENEZUELA</a>';
contenido('bancario');
       echo ' <form autocomplete="off" class="was-validated" method="post" action= "mensualidad_'.strtolower ($operador) .'.php">';
       //echo $status_usuario;
       // Your date
       $inicio = new DateTime(); // empty for now or pass any date string as param
       //echo $inicio->format('Y-m-d');
       //echo "<br>";
       $hoy = date('d/m/Y');
       // Adding
       // or even easier
       $fin = $inicio->modify('+1 month');
       $fin = $fin->format('d/m/Y');
       // Checking
       //echo $fin->format('Y-m-d');
       echo "<br>";
       echo '<div class="form-group">
       <label for="monto_mensualidad"><h5>Monto a pagar</h5>El monto a pagar para poder activar el servicio de Recargas '.$operador.' es de: <b>'. $mcf .' Bs.</b></label>
       <input type="hidden" name="monto" value="'.$mmo.'">
       <input type="hidden" name="concepto" value="'.$concepto.'">

       </div>';
       echo '<div class="alert alert-warning" role="alert"><h5>Vigencia de su Plan '.$operador.'</h5>Por ejemplo:<br>Aprobandose su pago hoy: <b>'. $hoy .'</b><br>Su renta venceria el <b>'. $fin .'</b>
       <input type="hidden" name="inicio" value="'.$hoy.'">
       <input type="hidden" name="fin" value="'.$fin.'">
       </div>';
       echo '
       <div class="form-group">
       <label for="banco_emisor">Desde Que banco Transfirio</label>
       <select class="custom-select" id="banco_emisor" name="banco_emisor" value="';
       echo $banco_emisor;
       echo '" required >
       <option value="">Seleccione:</option>';
       banco_emisor();
       echo '</select> <div class="invalid-feedback">Debe Seleccionar desde que banco efectuo su transferencia.</div>
       </div>
       <div class="form-group">
       <label for="banco_destino">A que Banco Transfirio</label>
       <select class="custom-select" id="banco_destino" name="banco_destino" value="';
       echo $banco_destino;
       echo '" required >
       <option value="">Seleccione:</option>';
       banco_destino();
       echo '</select>
       <div class="invalid-feedback">Debe Seleccionar a que banco usted efectuo su transferencia.</div>
       </div>
       <div class="form-group">
       <label for="nroTransf">Numero de Transferencia</label>
       <input pattern="[0-9]{8,15}" title = "Debe utilizar solo Numeros, Minimo 8 digitos y Maximo 15 digitos. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234"  type="text" class="form-control" id="nro_transf" aria-describedby="nro_transf" placeholder="Numero de Operacion Bancaria" name="nro_transf" value="';
       echo $nro_transf;
       echo '" required>
       <div class="invalid-feedback">Debe indicar el numero de operacion bancaria indicada por su Banco. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234</div>
       </div>
       <div class="form-group">
       <label for="ci_nro_cuenta">Cedula del Titular de la Cuenta Origen</label>
       <input  pattern="[0-9]{7,10}" title = "Debe utilizar solo Numeros, Minimo 7 digitos y Maximo 10 digitos"   type="text" class="form-control" id="ci_nro_cuenta" aria-describedby="ci_nro_cuenta" placeholder="Numero de Cedula Titular de la Cuenta Origen" name="ci_nro_cuenta" value="';
       echo $ci_nro_cuenta;
       echo '" required>
       <div class="invalid-feedback">Debe indicar el numero de cedula del titular de la cuenta desde donde usted efectuo su transferencia.</div>
       </div>
       <div class="form-group">
       <label for="fechaTransf">Fecha de su Transferencia</label>
       <input pattern="(?: 30)) | (? :(? : 0 [13578] | 1 [02]) - 31)) / (? :(?: 0 [1-9] | 1 [0-2]) - (?: 0 [1-9] | 1 [0 -9] | 2 [0-9]) | (? :( ?! 02) (?: 0 [1-9] | 1 [0-2]) / (?: 19 | 20) [0-9] {2}" title = "Debe utilizar el formato DD/MM/YYYY" type="date" class="form-control" id="fecha_transf" aria-describedby="fecha_transf" placeholder="Numero de Operacion Bancaria" name ="fecha_transf" value="';
       echo $fecha_transf;
       echo '" required>
       <div class="invalid-feedback">Debe Seleccione la fecha en que usted efectuo su transferencia.</div>
       </div>
       <input type="hidden" name="user" value="'.$usua.'">
       <input type="hidden" name="titulopag" value="'.$operador.'">
       <button type="submit" class="btn btn-primary" name="pago_mensualidad_operadoras_btn">Enviar</button>
       </form>';
  }
//   }else {
//
//         echo '<div class="alert alert-warning" role="alert"" >
//         <h3>SU USUARIO ESTA BLOQUEADO</h3>
//         <p>Si considera que es un error, favor ingrese al area de <a target="_BLANK" href= "http://www.jesuministrosymas.com.ve/contactenos" ><b>CONTACTENOS</b></a> para mas informacion.</p>
//     </div>';
//
// }
}


 ?>
