<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Pedidos";
	include('../funciones/functions.php');

    $id_pedido = ($_GET['id']);

    if (empty($id_pedido)) {
        echo "no existe";
        header("location: pedidos.php");
       }
?>
<?php include("includes/head.php"); ?>
<div class="container">
<h2>Enviar Pedido</h2>


<!-- notification message -->
<?php if (isset($_SESSION['msn_pedidos_entrega'])) : ?>
			<div class="alert alert-danger" role="alert"" >
				<h3>
					<?php
						echo $_SESSION['msn_pedidos_entrega'];
						unset($_SESSION['msn_pedidos_entrega']);
					?>
				</h3>
			</div>
<?php endif ?>

<?php preparar_entrega_pedido(); ?>


<script>
$(function() {
        $("input[type='text']:not(:disabled)").each(function() {
        var input = '#' + this.id;
        sumar_t(input);

        $(this).keyup(function() {
            sumar_t(input);
        })

    });

		function sumar_t(field) {
	//var valor = "111 2222 333 4444 555 666 888 888 999 100 110 120";
		valor = document.getElementsByName("lote")[0].value;
		var finalCount = 0;
		var primer = valor.split("	").join(" ");
		var segmentos = primer.split(" ");
		var suma = 0;
		var i = 0;


		segmentos.forEach(function(element)
		{
		  //console.log(element);
		  //console.log(i);
		  if(i == 0 || i %  6 == 0)
		  {
		    if(i == 0)
		    {
		        suma =  parseInt(element);
		    }
		    else
		    {
		        suma += parseInt(element);
		        console.log("Suma: ", suma ); //para el ejemplo > "Suma: " 888
						var finalCount = suma;
		    }
		  }
		  i += 1;
		});

		$('#finalcount').val(suma);

		}


    function word_count(field) {
        var number = 0;
        var matches = $(field).val().match(/\b/g);
        if (matches) {
            number = matches.length / 2;
        }
        wordCounts[field] = number;
        var finalCount = 0;
        $.each(wordCounts, function(k, v) {
            finalCount += v;
        });
        $('#finalcount').val(finalCount)
    }
});


</script>

</div>
<?php include("includes/footer.php"); ?>
