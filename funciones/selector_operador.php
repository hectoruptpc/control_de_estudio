  <?php


function selecionar_min_max($operador){
  global $db, $multiplo, $monto_minimo, $monto_maximo;
    $query ="SELECT pro, min, max
    FROM mensualidades
    WHERE operador = '$operador'";
    $resultado = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($resultado);

    $multiplo = $row['pro'];
    $monto_minimo = $row['min'];
    $monto_maximo = $row['max'];
}

  function selector_operador(){
    global $concepto, $operador, $link, $multiplo, $num_min, $text_num_min, $ph, $nro, $op, $debe_pagar_operador, $me, $img_card, $montos, $monto_minimo, $monto_maximo, $titulopag, $valor_divisa, $valor_cuenta_netflix, $link_recargas, $multiplo, $monto_minimo, $monto_maximo;

    $men_ex = ' usted puede realizar cortes cada hora o cada media hora segun usted lo necesite: <ul><li>Usted primero debe registrar en la plataforma los numeros y montos a los cuales desea efectuar las recargas. </li><li>Luego si no posee saldo a favor debe efectuar el pago del lote de recargas, se recomienda efectuar pagos Banesco - Banesco o en su defecto que sean transferencias del mismo Banco, es decir Transferencia Banco de Venezuela - Banco de Venezuela o Bancaribe - Bancaribe, de esta forma las recarga seran efectuada en los horarios de sincronizacion con la operadora.</li></ul>Recuerde que usted podra efectuar mas de un pago al dia segun los requerimientos de su negocio.';


      if ($operador == "Movilnet"){
          $concepto = "MENS_MOVILNET";

          if ($titulopag !== $operador) {
              $link = '<a class = "link" data-html="true" data-toggle="popover" title="Aca podra acceder directamente al modulo de Recargas '.$operador.'" href="recargas_'.strtolower($operador).'.php">RECARGAS '.strtoupper($operador).'</a>';
              $link_recargas = '<a href="recargas_'.strtolower ($operador).'.php">AQUI</a>';
          } else {
              $link = '<a class = "link" data-html="true" data-toggle="popover" title="Aca podra acceder directamente al modulo de Recargas '.$operador.'" href="recargas_'.strtolower($operador).'.php">RECARGAS '.strtoupper($operador).'</a>';
              $link_recargas = '<a href="recargas_'.strtolower ($operador).'.php">AQUI</a>';
          }
      //MENS_MOVILNET
      selecionar_min_max($operador);


          //$num_min = "^(?:([0-9]{10}))?(?:([0]{1}[2]{1}[1-9]{1}[1-9]{1}[0-9]{7}))?(?:([0]{1}[4]{1}[1,2]{1}[6]{1}[0-9]{7}))?$";
          //$num_min = "^(?:([0]{1}[2]{1}[1-9]{1}[1-9]{1}[0-9]{7}))?(?:([0]{1}[4]{1}[1,2]{1}[6]{1}[0-9]{7}))?$";
          $num_min = "^(?:([0]{1}[4]{1}[1,2]{1}[6]{1}[0-9]{7}))?$";
          $text_num_min = "Debe utilizar solo numeros de telefono MOVILNET Prepago debe usar los 11 digitos Ejemplo: 04161234567 o 04261234567.";
          //$ph = "Numero MOVILNET o CANTV TV";
          $ph = "Numero MOVILNET";
          defi_operadora($operador);

          $me = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Hola '.$_SESSION['user']['nombre'].'</strong> Aca podra generar las solicitudes de recargas a numeros <b>' .$operador .'</b>, con este sistema usted podra generar solicitudes de recargas a los servicios <b> TELEFONOS CELULARES MOVILNET</b>, '.$men_ex.'
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
        $img_card ='<img src="../images/operadoras/movilnet.png" class="rounded mx-auto d-block" alt="">';


            if (strlen ($nro) == 10){
              $op = "CANTV TV SATELITAL";
            }

            else if (strlen ($nro) == 11){
              $rest = substr("$nro", 0, -9);
              if ($rest=='02') {
                // code...
                $op = "TELF CANTV FIJO";
              }
              else {
                // code...
                $op = "TELF MOVILNET";
              }
            }

      }



      /***********/

      if ($operador == "Movistar"){
          $concepto = "MENS_MOVISTAR";

          if ($titulopag !== $operador) {
              $link = '<a class = "link" data-html="true" data-toggle="popover" title="Aca podra acceder directamente al modulo de Recargas '.$operador.'" href="recargas_'.strtolower($operador).'.php">RECARGAS '.strtoupper($operador).'</a>';
                $link_recargas = '<a href="recargas_'.strtolower ($operador).'.php">AQUI</a>';
          } else {
              $link = '<a class = "link" data-html="true" data-toggle="popover" title="Aca podra acceder directamente al modulo de Recargas '.$operador.'" href="recargas_'.strtolower($operador).'.php">RECARGAS '.strtoupper($operador).'</a>';
                $link_recargas = '<a href="recargas_'.strtolower ($operador).'.php">AQUI</a>';
          }
  //MENS_MOVISTAR

  selecionar_min_max($operador);

          $num_min = "^(?:([0-9]{8}))?(?:([0]{1}[4]{1}[1,2]{1}[4]{1}[0-9]{7}))?$";
          $text_num_min = "Debe utilizar solo Numeros, Si es MOVISTAR TV debe indicar 8 digitos Ejemplo: 12345678, si es un numero de telefono MOVISTAR debe usar 11 digitos Ejemplo: 04141234567 o 04241234567.";
          $ph = "Numero MOVISTAR o MOVISTAR TV";
          defi_operadora($operador);
          $me = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Hola '.$_SESSION['user']['nombre'].'</strong> Aca podra generar las solicitudes de recargas a numeros <b>' .$operador .'</b>, con este sistema usted podra generar solicitudes de recargas a los servicios <b> MOVISTAR TV o TELEFONOS CELULARES MOVISTAR</b>, '.$men_ex.'
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
        $img_card ='<img src="../images/operadoras/movistar.png" class="rounded mx-auto d-block" alt="">';


            if (strlen ($nro) == 8){
              $op = "MOVISTAR TV";
            }

            else if (strlen ($nro) == 11){
              $op = "TELF MOVISTAR";
            }

      }

      if ($operador == "Digitel"){
          $concepto = "MENS_DIGITEL";
          if ($titulopag !== $operador) {
              $link = '<a class = "link" data-html="true" data-toggle="popover" title="Aca podra acceder directamente al modulo de Recargas '.$operador.'" href="recargas_'.strtolower($operador).'.php">RECARGAS '.strtoupper($operador).'</a>';
                $link_recargas = '<a href="recargas_'.strtolower ($operador).'.php">AQUI</a>';
          } else {
              $link = '<a class = "link" data-html="true" data-toggle="popover" title="Aca podra acceder directamente al modulo de Recargas '.$operador.'" href="recargas_'.strtolower($operador).'.php">RECARGAS '.strtoupper($operador).'</a>';
                $link_recargas = '<a href="recargas_'.strtolower ($operador).'.php">AQUI</a>';
          }
          //MENS_DIGITEL
          selecionar_min_max($operador);

          $num_min = "[0]{1}[4]{1}[1]{1}[2]{1}[0-9]{7}";
          $text_num_min = "Debe utilizar solo Numeros de Telefono Digitel, debe usar 11 digitos Ejemplo: 04121234567.";
          $op = "TELF DIGITEL";
          $me = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Hola '.$_SESSION['user']['nombre'].'</strong> Aca podra generar las solicitudes de recargas a numeros <b>' .strtoupper($operador) .'</b>, '.$men_ex.'
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
        $ph = "Numero DIGITEL";
        defi_operadora($operador);
        $img_card ='<img src="../images/operadoras/digitel.png" class="rounded mx-auto d-block" alt="">';


      }

      if ($operador == "Directv"){
          $concepto = "MENS_DIRECTV";
          if ($titulopag !== $operador) {
              $link = '<a class = "link" data-html="true" data-toggle="popover" title="Aca podra acceder directamente al modulo de Recargas '.$operador.'" href="mensualidad_'.strtolower($operador).'.php">RECARGAS '.strtoupper($operador).'</a>';
          } else {
              $link = '<a class = "link" data-html="true" data-toggle="popover" title="Aca podra acceder directamente al modulo de Recargas '.$operador.'" href="recargas_'.strtolower($operador).'.php">RECARGAS '.strtoupper($operador).'</a>';
          }
          //MENS_DIRECTV
          selecionar_min_max($operador);

          $num_min = "[0-9]{12}";
          $text_num_min = "Debe utilizar solo Numeros de tarjetas de acceso Directv - Simple TV, debe usar 12 digitos Ejemplo: 123456789123.";
          $op = "TARJ DIRECTV";
          $me = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Hola '.$_SESSION['user']['nombre'].'</strong> Aca podra generar las solicitudes de recargas a numeros de tarjetas <b>' .strtoupper($operador) .'</b>, '.$men_ex.'
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
        $ph = "Numero Tarjeta DIRECTV";
        defi_operadora($operador);
        $img_card ='<img src="../images/operadoras/directv.png" class="rounded mx-auto d-block" alt="">';

      }

      if ($operador == "Inter"){
        $concepto = "MENS_INTER";
        if ($titulopag !== $operador) {
            $link = '<a class = "link" data-html="true" data-toggle="popover" title="Aca podra acceder directamente al modulo de Recargas '.$operador.'" href="mensualidad_'.strtolower($operador).'.php">RECARGAS '.strtoupper($operador).'</a>';
        } else {
            $link = '<a class = "link" data-html="true" data-toggle="popover" title="Aca podra acceder directamente al modulo de Recargas '.$operador.'" href="recargas_'.strtolower($operador).'.php">RECARGAS '.strtoupper($operador).'</a>';
        }
        //MENS_INTER
          selecionar_min_max($operador);
        $num_min = "[0-9]{10}";
        $text_num_min = "Debe utilizar solo Numeros de Abonado Inter, debe usar 10 digitos Ejemplo: 1234567891.";
        $op = "ABON INTER";
        $me = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Hola '.$_SESSION['user']['nombre'].'</strong> Aca podra generar las solicitudes de recargas a numeros de Abonados <b>' .strtoupper($operador) .'</b>, '.$men_ex.'
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>';
      $ph = "Numero de Abonado INTER";
      defi_operadora($operador);
      $img_card ='<img src="../images/operadoras/inter.png" class="rounded mx-auto d-block" alt="">';
    }

    if ($operador == "General"){
        $concepto = "MENS_GENERAL";
        if ($titulopag !== $operador) {
            $link = '<a class = "link" data-html="true" data-toggle="popover" title="Aca podra acceder directamente al modulo de Recargas '.$operador.'" href="mensualidad_'.strtolower($operador).'.php">RECARGAS '.strtoupper($operador).'</a>';
        } else {
            $link = '<a class = "link" data-html="true" data-toggle="popover" title="Aca podra acceder directamente al modulo de Recargas '.$operador.'" href="recargas_'.strtolower($operador).'.php">RECARGAS '.strtoupper($operador).'</a>';
        }
        selecionar_min_max($operador);


    }
    if ($operador == "Netflix"){
        $concepto = "MENS_NETFLIX";
        if ($titulopag !== $operador) {
            $link = '<a class = "link" data-html="true" data-toggle="popover" title="Aca podra acceder directamente al modulo de Recargas '.$operador.'" href="mensualidad_'.strtolower($operador).'.php">RECARGAS '.strtoupper($operador).'</a>';
        } else {
            $link = '<a class = "link" data-html="true" data-toggle="popover" title="Aca podra acceder directamente al modulo de Recargas '.$operador.'" href="recargas_'.strtolower($operador).'.php">RECARGAS '.strtoupper($operador).'</a>';
        }
  //MENS_NETFLIX
        $valor_divisa = '75800';
        $valor_cuenta_netflix = '10';

      $img_card ='<img src="../images/operadoras/netflix.png" class="rounded mx-auto d-block" alt="">';



    }

  }

  function defi_operadora($operador){
    global $debe_pagar_operador;

    $debe_pagar_operador = '<div class="alert alert-danger" role="alert"" >	<h3> LO SENTIMOS USTED NO HA EFECTUADO EL PAGO CORRESPONDIENTE AL PERIODO ACTUAL</h3></div><div class="btn-group-vertical"><a class="btn btn-outline-primary" href="mensualidad_'.strtolower ($operador) .'.php" role="button">Declare su Pago de Mensualidad Para uso de plataforma '.$operador.'</a>
    <a class="btn btn-outline-success" href="recargas_'.strtolower ($operador) .'_sin_plan.php" role="button">Tambien puedes efectuar recargas sin necesidad de pagar Mensualidad</a></div>';

    return $debe_pagar_operador;
  }

 ?>
