<?php
// .dios/footer_dios.php - Footer específico para el Panel DIOS (VERSIÓN CLARA)
?>
</div> <!-- Cierre del dios-container -->

<footer class="dios-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-left">
                <i class="fas fa-crown" style="color: #ffc107;"></i> <strong>Panel de Control DIOS</strong>
            </div>
            <div class="col-md-6 text-right">
                <i class="fas fa-university"></i> UPTPC - Universidad Politécnica Territorial de Puerto Cabello<br>
                <small>Sistema de Control de Estudios - Acceso Restringido</small>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12 text-center">
                <small>&copy; <?php echo date('Y'); ?> - Todos los derechos reservados</small>
            </div>
        </div>
    </div>
</footer>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<script>
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover({
        trigger: 'hover',
        placement: 'auto'
    });
});

function confirmarAccion(mensaje) {
    return confirm(mensaje);
}

setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
</body>
</html>