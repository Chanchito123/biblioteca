<?php
session_start(); // Iniciar sesión si no está activa
include 'db.php'; // Incluir conexión a la base de datos si es necesario
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Contacto - Biblioteca Universitaria</title>
</head>
<body>

<?php include 'header.php'; ?> <!-- Incluir el menú y la cabecera -->

<div class="content">
    <h2>Contacto</h2>
    <p>Puedes encontrarnos en nuestras redes sociales:</p>
    <ul>
        <li>
            <a href="https://api.whatsapp.com/send?phone=5212761265066&text&context=ARA48swVezjl4d1mL9oeYNOpv4t0tw9rYjYJqU704zvnC8ClvCmbQJr4aSY6aunX00L3PT4WD-q8eL9JTRViuiV-KhvVQGI7Z1wfy-pDtoidsex1AuxjwfJqs5lQxiKruGcQcaYZWmf4uCR-2g&source&app=facebook" target="_blank" rel="noopener noreferrer">
                <img src="images/whatsapp-logo1.png" alt="WhatsApp" width="50" height="50"/>
            </a>
        </li>
        <li>
            <a href="https://www.facebook.com/utdeoriental/?locale=es_LA" target="_blank" rel="noopener noreferrer">
                <img src="images/facebook-logo.png" alt="Facebook" width="50" height="50"/>
            </a>
        </li>
        <li>
            <a href="https://www.youtube.com/user/utoriental" target="_blank" rel="noopener noreferrer">
                <img src="images/youtube-logo.png" alt="" width="50" height="50"/>
            </a>
        </li>
    </ul>
</div>

<?php include 'footer.php'; ?> <!-- Incluir el footer -->

<script src="script.js"></script> <!-- Incluir el archivo JavaScript -->

</body>
</html>