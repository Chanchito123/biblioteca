<?php
// Iniciar la sesión solo si no está activa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<header>
<img fetchpriority="high" width="750" height="71" src="https://utdeoriental.edu.mx/wp-content/uploads/2023/06/headrer-web_Mesa-de-trabajo-1-1024x97.png" class="attachment-large size-large wp-image-15400" alt="" srcset="https://utdeoriental.edu.mx/wp-content/uploads/2023/06/headrer-web_Mesa-de-trabajo-1-1024x97.png 1024w, https://utdeoriental.edu.mx/wp-content/uploads/2023/06/headrer-web_Mesa-de-trabajo-1-300x28.png 300w, https://utdeoriental.edu.mx/wp-content/uploads/2023/06/headrer-web_Mesa-de-trabajo-1-1536x146.png 1536w, https://utdeoriental.edu.mx/wp-content/uploads/2023/06/headrer-web_Mesa-de-trabajo-1-2048x194.png 2048w" sizes="(max-width: 750px) 100vw, 750px">
    <h1>Bienvenido a la Biblioteca Universitaria</h1>
    <p>Gestiona el préstamo de libros de manera fácil y rápida.</p>
</header>

<nav>
    <ul>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <li><a href="index.php">Inicio</a></li> <!-- Enlace a la página principal -->
            <li><a href="contacto.php">Contacto</a></li> <!-- Enlace al apartado de contacto -->
            <li><a href="register.php">Registrar Usuario</a></li>
            <li><a href="login.php">Iniciar Sesión</a></li>
        <?php else: ?>
            <li><a href="index.php">Inicio</a></li> <!-- Enlace a la página principal -->
            <li><a href="contacto.php">Contacto</a></li> <!-- Enlace al apartado de contacto -->
            <li><a href="reservados.php">Libros Reservados</a></li> <!-- Enlace a los libros reservados -->
            <li><a href="historial.php">Historial de Préstamos</a></li> <!-- Enlace al historial -->
            <li><a href="books.php">Ver Libros</a></li> <!-- Enlace para ver libros -->
            <li><a href="logout.php">Cerrar Sesión</a></li> <!-- Opción para cerrar sesión -->
        <?php endif; ?>
        
    </ul>
</nav>
