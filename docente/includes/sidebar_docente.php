<?php
// sidebar_docente.php - Barra lateral corregida para docentes
?>
<style>
    /* Estilos principales de la barra lateral */
    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: #f8f9fa; /* Fondo claro */
        color: #333; /* Texto oscuro */
        transition: all 0.3s ease;
        z-index: 1000;
        overflow-y: auto;
        padding-top: 70px;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        border-right: 1px solid #e0e0e0;
    }
    
    .sidebar.collapsed {
        width: 70px;
    }
    
    /* Contenedor del botón de toggle */
    .sidebar-toggle-container {
        position: fixed;
        left: 250px;
        top: 50vh;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        z-index: 1002;
        transition: left 0.3s ease;
    }
    
    /* Cuando el sidebar está colapsado */
    .sidebar.collapsed + .sidebar-toggle-container {
        left: 70px;
    }
    
    /* Estilos del botón de toggle */
    .sidebar-toggle {
        background: #006633; /* Verde institucional */
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
        background: #004d26;
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
    
    /* Estilos del menú */
    .menu-docente {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .menu-docente li {
        margin-bottom: 5px;
    }
    
    .menu-docente a {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: #333; /* Texto oscuro */
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
        position: relative;
        border-left: 4px solid transparent;
    }
    
    .menu-docente a:hover {
        background: #e8f5e9; /* Verde muy claro al pasar el mouse */
        color: #006633; /* Texto verde */
        border-left: 4px solid #006633;
    }
    
    .menu-docente a i {
        margin-right: 15px;
        min-width: 20px;
        font-size: 1.1rem;
        color: #006633; /* Iconos verdes */
    }
    
    .menu-docente a:hover i {
        color: #004d26; /* Iconos más oscuros al pasar el mouse */
    }
    
    /* Estilos para versión colapsada */
    .sidebar.collapsed .menu-text {
        display: none;
    }
    
    .sidebar.collapsed .menu-docente a {
        justify-content: center;
        padding: 15px 5px;
    }
    
    .sidebar.collapsed .menu-docente a i {
        margin-right: 0;
        font-size: 1.3rem;
    }
    
    .sidebar.collapsed .menu-docente a::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background: #006633;
        color: white;
        padding: 8px 12px;
        border-radius: 4px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s;
        margin-left: 15px;
        font-size: 0.9rem;
        z-index: 1001;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .sidebar.collapsed .menu-docente a:hover::after {
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
            background: #006633;
            border: none;
            color: white;
            border-radius: 4px;
            padding: 8px 12px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .mobile-sidebar-toggle i {
            margin-right: 8px;
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
    <i class="fas fa-bars"></i> Menú
</button>

<!-- Barra lateral -->
<div class="sidebar" id="sidebar">
    <ul class="menu-docente">
        <li><a href="mis_cursos.php" data-tooltip="Mis Cursos"><i class="fas fa-chalkboard-teacher"></i> <span class="menu-text">Mis Cursos</span></a></li>
        <li><a href="calificaciones.php" data-tooltip="Calificaciones"><i class="fas fa-check-double"></i> <span class="menu-text">Calificaciones</span></a></li>
        <li><a href="asistencias.php" data-tooltip="Asistencias"><i class="fas fa-clipboard-list"></i> <span class="menu-text">Asistencias</span></a></li>
        <li><a href="materiales.php" data-tooltip="Materiales"><i class="fas fa-file-upload"></i> <span class="menu-text">Materiales</span></a></li>
        <li><a href="mensajes.php" data-tooltip="Mensajes"><i class="fas fa-comments"></i> <span class="menu-text">Mensajes</span></a></li>
        <li><a href="perfil.php" data-tooltip="Mi Perfil"><i class="fas fa-user-cog"></i> <span class="menu-text">Mi Perfil</span></a></li>
        <li><a href="home.php?logout='1'" data-tooltip="Cerrar Sesión"><i class="fas fa-sign-out-alt"></i> <span class="menu-text">Cerrar Sesión</span></a></li>
    </ul>
</div>

<!-- Contenedor del botón de toggle -->
<div class="sidebar-toggle-container">
    <button class="sidebar-toggle" id="sidebarToggle" title="Ocultar/Mostrar menú">
        <i class="fas fa-chevron-right"></i>
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