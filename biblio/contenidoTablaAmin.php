<?php
/*======================================================*/
/*===== archivo para las consultas de las carreras =====*/
/*======================================================*/
  include("conexion.php");
  session_start();

         

          

                
               

                $result = $conn->query("SELECT * FROM user where tipo != '1' ORDER BY nom asc");



                if (isset($_POST['mos'])) {

                


                
                $mos = trim($_POST['mos']);
                $text = trim($_POST['txt']);
               
                
                

                    $result = $conn->query("SELECT * FROM user
                        where tipo != '1'
                        and (
                         nom like '%$text%'
                         or pas like '%$text%'
                        )
                        ORDER BY nom asc
                          LIMIT $mos");
                

                }      

            




?>




<?php  if (mysqli_num_rows($result) > 0) { ?>

<?php  while ($row = mysqli_fetch_array($result)) {  ?>



     <tr>
        <td><?php echo $row['nom'] ?> </td>
        <td><?php echo $row['pas'] ?></td>

        <?php if (isset($_SESSION['tipoUser'])) { ?>

            <td class="acciones">
                <a class="alumrow editar" 
         title="Editar elemento" href="index.php?formAdmin=1&id=<?php echo $row['id']  ?>" style="text-decoration: none;">
          <!--Editar-->
                                <button  class="pencil">
                
                                    <i class="icon icon-pencil2"></i>
                  
                                </button>
        </a>
                <a name="bin" 
        class="alumrow" 
        title="Eliminar elemento" href="eliminarAdmin.php?id=<?php echo $row['id']  ?>">
          <!--Eliminar-->
       
                              <button  class="denegar">
                
                                    <i class="icon icon-bin"></i>
                  
                                </button>

        </a>
            </td>

        <?php } ?>
        

       </tr> 

    
<?php } ?>

<?php }else{ ?>

  <tr>
    <td style="text-align: center;" colspan ="4">No hay datos-Disponibles </td>
  </tr>


<?php } ?>


<script src="js/eliminarRowBD.js"></script>
<script src="js/editarRow.js"></script>
