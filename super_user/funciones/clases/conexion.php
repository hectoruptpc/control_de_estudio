<?php

class conectar{
  public function conexion(){
    $conexion=mysqli_connect('localhost',
                  'root',
                  '01012023',
                  'proyecto_tsu');
    return $conexion;
  }
}

 ?>
