<?php
function analizar_mensualidad3(){
    global $db, $usua, $mes_de_pago_actual, $limite_basico,
    $limite_avanzado, $limite_vip, $operador, $planes, $fecha_actual_sistema, $concepto, $m_dias_r, $como_pagar, $me, $pago_mensualidad, $cabecera_privada;

    $montos_permitidos ="";
    $limite_base = 0;

    selector_operador();


  analisis_dias_restantes();
  $cabecera_privada = $como_pagar;
  $cabecera_privada .= '<img src="../images/operadoras/'.strtolower ($operador) .'.png" class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}" alt="">';
  $cabecera_privada .= $m_dias_r;
  
  }


 ?>
