<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');



$titulopag = "Permutas";
include('../../funciones/functions.php');

$query = "SELECT CONCAT(c1,' ',c2,' ',c3,' ',c4) TEXTO FROM ( SELECT c1 FROM creador ) cc1, ( SELECT c2 FROM creador ) cc2, ( SELECT c3 FROM creador ) cc3, ( SELECT c4 FROM creador ) cc4";
$result = mysqli_query($db, $query);
$n = 1;
while ($rows =  mysqli_fetch_assoc($result))
{
$texto = $rows['TEXTO'];
if ($texto != null){
echo "<p> $texto</p>";
//$n ++;
}
}
$db->close();
?>