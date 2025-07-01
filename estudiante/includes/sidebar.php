<?php
// sidebar.php - Barra lateral del estudiante
?>
<style>
    /* Estilos principales de la barra lateral */
    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: #003366;
        color: white;
        transition: all 0.3s ease;
        z-index: 1000;
        overflow-y: auto;
        padding-top: 70px;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }
    
    .sidebar.collapsed {
        width: 70px;
    }
    
    /* Contenedor del botón de toggle - AHORA COMPLETAMENTE FUERA DEL SIDEBAR */
    .sidebar-toggle-container {
        position: fixed;
        left: 250px; /* Posición inicial al lado del sidebar */
        top: 50vh;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        z-index: 1002;
        transition: left 0.3s ease;
    }
    
    /* Cuando el sidebar está colapsado */
    .sidebar.collapsed + .sidebar-toggle-container {
        left: 70px; /* Nueva posición cuando está colapsado */
    }
    
    /* Estilos del botón de toggle */
    .sidebar-toggle {
        background: #003366;
        border: 2px solid white;
        color: white;
        border-radius: 50%;
        width: 100%;
        height: 100%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }
    
    .sidebar-toggle:hover {
        background: #002244;
        transform: scale(1.1);
    }
    
    .sidebar-toggle i {
        transition: transform 0.3s ease;
        font-size: 14px;
    }


    /* Animación de giro cuando está colapsado */
    .sidebar.collapsed + .sidebar-toggle-container .sidebar-toggle i {
        transform: rotate(180deg);
    }
    
    .sidebar.collapsed + .sidebar-toggle-container .sidebar-toggle i {
        transform: rotate(180deg);
    }
    
    /* Resto de estilos del menú (igual que antes) */
    .menu-estudiante {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .menu-estudiante li {
        margin-bottom: 5px;
    }
    
    .menu-estudiante a {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: white;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
        position: relative;
    }
    
    .menu-estudiante a:hover {
        background: #002244;
    }
    
    .menu-estudiante a i {
        margin-right: 15px;
        min-width: 20px;
        font-size: 1.1rem;
    }
    
    .sidebar.collapsed .menu-text {
        display: none;
    }
    
    .sidebar.collapsed .menu-estudiante a {
        justify-content: center;
        padding: 15px 5px;
    }
    
    .sidebar.collapsed .menu-estudiante a i {
        margin-right: 0;
        font-size: 1.3rem;
    }
    
    .sidebar.collapsed .menu-estudiante a::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background: #002244;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s;
        margin-left: 15px;
        font-size: 0.9rem;
        z-index: 1001;
    }
    
    .sidebar.collapsed .menu-estudiante a:hover::after {
        opacity: 1;
        visibility: visible;
        margin-left: 10px;
    }
    
    /* Estilos para móvil */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            z-index: 1001;
        }
        
        .sidebar.open {
            transform: translateX(0);
        }
        
        .sidebar-toggle-container {
            display: none;
        }
        
        .mobile-sidebar-toggle {
            display: block;
            position: fixed;
            left: 15px;
            top: 15px;
            z-index: 1000;
            background: #003366;
            border: none;
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }
    
    @media (min-width: 769px) {
        .mobile-sidebar-toggle {
            display: none !important;
        }
    }
</style>

<!-- Botón para móvil -->
<button class="mobile-sidebar-toggle" id="mobileSidebarToggle" style="display: none;">
    <i class="fas fa-bars"></i>
</button>

<!-- Barra lateral -->
<div class="sidebar" id="sidebar">
    <ul class="menu-estudiante">
        <li><a href="mis_clases.php" data-tooltip="Mis Clases"><i class="fas fa-book"></i> <span class="menu-text">Mis Clases</span></a></li>
        <li><a href="mis_notas.php" data-tooltip="Mis Notas"><i class="fas fa-check-circle"></i> <span class="menu-text">Mis Notas</span></a></li>
        <li><a href="mis_asistencias.php" data-tooltip="Mis Asistencias"><i class="fas fa-clipboard-check"></i> <span class="menu-text">Mis Asistencias</span></a></li>
        <li><a href="materiales_estudio.php" data-tooltip="Materiales"><i class="fas fa-file-download"></i> <span class="menu-text">Materiales</span></a></li>
        <li><a href="mensajes.php" data-tooltip="Mensajes"><i class="fas fa-envelope"></i> <span class="menu-text">Mensajes</span></a></li>
        <li><a href="horarios.php" data-tooltip="Horarios"><i class="fas fa-calendar-alt"></i> <span class="menu-text">Horarios</span></a></li>
        <li><a href="mi_perfil.php" data-tooltip="Mi Perfil"><i class="fas fa-user-cog"></i> <span class="menu-text">Mi Perfil</span></a></li>
        <li><a href="home.php?logout='1'" data-tooltip="Cerrar Sesión"><i class="fas fa-sign-out-alt"></i> <span class="menu-text">Cerrar Sesión</span></a></li>
    </ul>
</div>

<!-- Contenedor del botón de toggle -->
<div class="sidebar-toggle-container">
    <button class="sidebar-toggle" id="sidebarToggle" title="Ocultar/Mostrar menú">
        <i class="fas fa-chevron-right"></i> <!-- Cambiado a flecha derecha inicial -->
    </button>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const toggleContainer = document.querySelector('.sidebar-toggle-container');
    
    // Estado inicial
    let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    let isMobile = window.innerWidth <= 768;
    
    // Función para actualizar el estado
    function updateSidebarState() {
        isMobile = window.innerWidth <= 768;
        
        if (isMobile) {
            mobileSidebarToggle.style.display = 'block';
            sidebar.classList.remove('collapsed');
            toggleContainer.style.display = 'none';
        } else {
            mobileSidebarToggle.style.display = 'none';
            sidebar.classList.toggle('collapsed', isCollapsed);
            toggleContainer.style.display = 'block';
        }
    }
    
    // Toggle para desktop
    sidebarToggle.addEventListener('click', function() {
        if (!isMobile) {
            isCollapsed = !isCollapsed;
            localStorage.setItem('sidebarCollapsed', isCollapsed);
            sidebar.classList.toggle('collapsed');
        }
    });
    
    // Toggle para mobile
    mobileSidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('open');
    });
    
    // Cerrar sidebar al hacer clic fuera en móvil
    document.addEventListener('click', function(e) {
        if (isMobile && !sidebar.contains(e.target) && e.target !== mobileSidebarToggle) {
            sidebar.classList.remove('open');
        }
    });
    
    // Actualizar al cambiar tamaño
    window.addEventListener('resize', updateSidebarState);
    
    // Estado inicial
    updateSidebarState();
    
    // Ajustar padding superior según el header
    const adjustSidebarPadding = () => {
        const header = document.querySelector('header');
        if (header) {
            const headerHeight = header.offsetHeight;
            sidebar.style.paddingTop = `${headerHeight}px`;
            toggleContainer.style.top = `calc(50vh + ${headerHeight/2}px)`;
        }
    };
    
    // Ajustar inicialmente y cuando cambie el tamaño
    adjustSidebarPadding();
    window.addEventListener('resize', adjustSidebarPadding);
});
</script>