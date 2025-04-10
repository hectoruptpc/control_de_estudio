<!-- Footer -->
<footer class="footer page-footer font-small pt-4 navbar-fixed-bottom">


    <!-- Footer Links -->
    <div class="container-fluid text-center text-md-left">

      <!-- Grid row -->
      <div class="row">

        <!-- Grid column -->
        <div class="col-md-9 mt-md-0 mt-3">

          <!-- Content -->

          <?php comentarios(); ?>

        </div>
        <!-- Grid column -->

        <hr class="clearfix w-100 d-md-none pb-3">

        <!-- Grid column -->
        <div class="col-md-3 mb-md-0 mb-3">

            <!-- Links -->
            <h5 class="text-uppercase">Link de Interes</h5>

            <ul class="list-unstyled">
              <li>
              <a class="nav-link active" href="<?php echo $pag_web; ?>/usuario/terminos_y_condiciones.php">Terminos y Condiciones</a>
              </li>
              <li>
              <a class="nav-link active" href="<?php echo $pag_web; ?>/usuario/como_funciona.php">Como Usar el Sitio</a>
              </li>
              <li>
              <a class="nav-link active" href="acercade.php">Acerca de</a>
              </li>
              <li>
              <a target="_blank" class="nav-link active" href="http://www.jesuministrosymas.com.ve/contactenos">Contactenos</a>
              </li>


            </ul>

          </div>
          <!-- Grid column -->



    </div>
    <!-- Footer Links -->
    <div class="footer-copyright text-center py-3">
    <?php echo $logopertenenciag; ?>
    </div>
    <footer class="footer mt-auto py-3 bg-light">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <p>Copyright © 2007 Jose Herrera</p>

        <p>
          Licenciado bajo la 
          <a href="https://www.apache.org/licenses/LICENSE-2.0" target="_blank">Licencia Apache, Versión 2.0</a>.
          <a href="#licenciaApacheCollapse" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="licenciaApacheCollapse">Ver texto completo.</a> 
        </p>
        <div class="collapse" id="licenciaApacheCollapse">
          <div class="card card-body">
         <p> Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at</p>

   <p> http://www.apache.org/licenses/LICENSE-2.0</p>

<p>Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.</p>
            <!-- Complemento en español -->
            <p>
              **Nota:**  Si bien el núcleo JavaScript de este software se proporciona bajo la licencia Creative Commons, 
              el software en su totalidad (incluyendo código, documentación y otros archivos) está licenciado 
              bajo la  <a href="https://www.apache.org/licenses/LICENSE-2.0" target="_blank">Licencia Apache, Versión 2.0</a>. 
              Al utilizar, modificar o distribuir este software, usted acepta los términos de ambas licencias.
            </p>
          </div>
        </div>

        <p>Algunos derechos reservados. Para más información sobre esta licencia y el uso de recursos de Creative Commons, visita  <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank">creativecommons.org <img src="<?php echo $pag_web; ?>/images/by-nc-sa.png" width="60" height="20" ></a>.</p>
      </div>
    </div>
  </div>
</footer>

    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">&copy <?php echo date('Y');?> - Jose Herrera
<a href="http://www.jesuministrosymas.com.ve" target="_blank" style="color: #0027ff">Acceda a Nuestro Sitio</a>
    </div>
    <!-- Copyright -->

    </div>
  </footer>
  <!-- Footer -->



<?php echo $bootstrap_footer; ?>


<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({
    trigger: 'hover',
    placement: 'auto'
        });
});

$('#overlay').modal('show');

function mayus(e) {
  const start = e.selectionStart;
  const end = e.selectionEnd;
  e.value = e.value.toUpperCase();
  e.selectionStart = start;
  e.selectionEnd = end;
}

</script>

<?php visita(); ?>

</body>
</html>
