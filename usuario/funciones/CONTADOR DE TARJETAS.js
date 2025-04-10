
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
//var valor = "111 2222 333 4444 555 666 888 888 999 100 110 120"; //ESTE DEJAR DESACTIVADO
	valor = document.getElementsByName("lote")[0].value;
	var finalCount = 0;
	var primer = valor.split("	").join(" ");
	var segmentos = primer.split(" ");
	var suma = 0;
	var i = 0;


	segmentos.forEach(function(element)
	{
	  //console.log(element); //ESTE DEJAR DESACTIVADO
	  //console.log(i); //ESTE DEJAR DESACTIVADO
	  if(i == 0 || i %  6 == 0)
	  {
	    if(i == 0)
	    {
	        suma =  parseInt(element);
	    }
	    else
	    {
	        suma += parseInt(element);
					var finalCount = suma;
	    }
	  }
	  i += 1;
	});
//aa = suma +" Bs"; //ESTE DEJAR DESACTIVADO
	$('#resumen_suma').val(suma);

	}


  function word_count(field) {
      var number = 0;
      var matches = $(field).val().match(/\b/g);
      if (matches) {
          number = matches.length / 2;
      }
      wordCounts[field] = number;
      var resumen_suma = 0;
      $.each(wordCounts, function(k, v) {
          resumen_suma += v;
      });
      $('#resumen_suma').val(resumen_suma)
  }
});


</script>
