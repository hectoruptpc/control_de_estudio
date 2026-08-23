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
<script>window.jQuery || document.write('<script src="../funciones/jquery/jquery-3.7.1.js"><\/script>')</script>
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

<script>
// BUSCADOR EN TIEMPO REAL
$(document).ready(function() {
    var timeoutId;
    var DIOS_AJAX_TOKEN = '<?php echo isset($_SESSION["dios_ajax_token"]) ? addslashes($_SESSION["dios_ajax_token"]) : ''; ?>';
    
    function cargarUsuarios(searchTerm, page) {
        if (page === undefined) page = 1;
        
        $('#tablaUsuarios').html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Cargando...</div>');
        
        $.ajax({
            url: 'ajax_usuarios.php',
            type: 'GET',
            data: { buscar: searchTerm, pagina: page, token: DIOS_AJAX_TOKEN },
            dataType: 'json',
            success: function(response) {
                if (response.html) {
                    $('#tablaUsuarios').html(response.html);
                    $('#paginacionUsuarios').html(response.paginacion);
                    $('#resultadosInfo').html('<i class="fas fa-users"></i> Total: ' + response.total + ' usuarios | Mostrando ' + response.mostrando);
                }
            },
            error: function(xhr, status, error) {
                $('#tablaUsuarios').html('<div class="alert alert-danger">Error al cargar los datos: ' + error + '</div>');
            }
        });
    }
    
    // Búsqueda automática al escribir (con delay de 500ms)
    $('#searchInput').on('input', function() {
        var searchTerm = $(this).val();
        clearTimeout(timeoutId);
        timeoutId = setTimeout(function() {
            cargarUsuarios(searchTerm, 1);
            if (searchTerm.length > 0) {
                $('#clearSearch').show();
            } else {
                $('#clearSearch').hide();
            }
        }, 500);
    });
    
    // Botón de búsqueda manual como respaldo
    $('#searchBtn').on('click', function() {
        var searchTerm = $('#searchInput').val();
        cargarUsuarios(searchTerm, 1);
        if (searchTerm.length > 0) {
            $('#clearSearch').show();
        } else {
            $('#clearSearch').hide();
        }
    });
    
    // Limpiar búsqueda
    $('#clearSearch').on('click', function() {
        $('#searchInput').val('');
        cargarUsuarios('', 1);
        $(this).hide();
    });
    
    // Paginación dinámica
    $(document).on('click', '#paginacionUsuarios .page-link', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        var searchTerm = $('#searchInput').val();
        if (page && !$(this).parent().hasClass('disabled')) {
            cargarUsuarios(searchTerm, page);
            $('html, body').animate({ scrollTop: $('#tablaUsuarios').offset().top - 100 }, 300);
        }
    });
});
</script>
</body>
</html>