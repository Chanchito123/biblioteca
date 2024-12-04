<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST['nombre']);
    $carrera = htmlspecialchars($_POST['carrera']);
    $matricula = htmlspecialchars($_POST['matricula']);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    
    // Encriptar la contraseña
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insertar en la base de datos
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, carrera, matricula, email, password) VALUES (?, ?, ?, ?, ?)");
    
    try {
        if ($stmt->execute([$nombre, $carrera, $matricula, $email, $password])) {
            header("Location: login.php");
            exit();
        } else {
            echo "<p class='error'>Error al registrar el usuario.</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>"; // Muestra el error específico
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="regis.css"> <!-- Asegúrate de que este archivo contenga tu CSS -->
    <title>Registro</title>
</head>
<body>
    <div class="container">
        <h2>Registro</h2>
        <form method="POST" action="">
            <div>
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required placeholder="Nombre" class="text">
            </div>

            <div>
                <label for="carrera">Carrera:</label>
                <select name="carrera" id="carrera" required class="text">
                    <option value="" disabled selected>Seleccione una carrera</option>
                    <option value="Ingeniería en Sistemas">Ingeniería en Sistemas</option>
                    <option value="Ingeniería Industrial">Ingeniería Industrial</option>
                    <option value="Licenciatura en Administración">Licenciatura en Administración</option>
                    <option value="Licenciatura en Psicología">Licenciatura en Psicología</option>
                    <option value="Ingeniería Civil">Ingeniería Civil</option>
                    <option value="Arquitectura">Arquitectura</option>
                    <option value="Licenciatura en Contaduría">Licenciatura en Contaduría</option>
                    <option value="Licenciatura en Mercadotecnia">Licenciatura en Mercadotecnia</option>
                    <option value="Ingeniería Electrónica">Ingeniería Electrónica</option>
                    <option value="Licenciatura en Derecho">Licenciatura en Derecho</option>
                    <option value="Licenciatura en Ciencias de la Computación">Licenciatura en Ciencias de la Computación</option>
                    <option value="Ingeniería Mecánica">Ingeniería Mecánica</option>
                    <option value="Licenciatura en Comunicación">Licenciatura en Comunicación</option>
                    <option value="Licenciatura en Diseño Gráfico">Licenciatura en Diseño Gráfico</option>
                </select>
            </div>

            <div>
                <label for="matricula">Matrícula:</label>
                <input type="text" name="matricula" required placeholder="Matrícula" class="text">
            </div>

            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" required placeholder="Email" class="text">
            </div>

            <div>
                <label for="password">Contraseña:</label>
                <input type="password" name="password" required placeholder="Contraseña" class="text">
            </div>

            <input type="submit" value="Registrar" class="submit">
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></p>
    </div>
</body>
</html>

<style>
html, body {
    align-items: center;
    background: #f2f4f8;
    border: 0;
    display: flex;
    font-family: Helvetica, Arial, sans-serif;
    font-size: 16px;
    height: 100%;
    justify-content: center;
    margin: 0;
    padding: 0;
}

.container {
    width: 100%;
    max-width: 400px; /* Ancho máximo del formulario */
    margin: 100px auto; /* Centrar verticalmente */
    padding: 20px;
    background-color: white; /* Fondo blanco para el contenedor */
    border-radius: 8px; /* Bordes redondeados */
    box-shadow: 0 1rem 1rem -0.75rem rgba(0,0,0,0.125); /* Sombra suave */
}

h2 {
    text-align: center;
    color: #007bff; /* Color azul para el título */
}

input[type=text], input[type=email], input[type=password], select {
    width: 100%;
    padding: 10px;
    margin-top: 10px; /* Espaciado superior */
    border: 1px solid rgba(0, 0, 0, 0.125); /* Borde gris claro */
    border-radius: 5px; /* Bordes redondeados */
}

input[type=text]:focus, input[type=email]:focus, input[type=password]:focus {
    border-color: #007bff; /* Cambio de color al enfocar */
}

input[type=submit] {
    width: 100%;
    padding: 10px;
    background-color: #007bff; /* Color azul del botón */
    color: white; /* Texto blanco en el botón */
    border: none; /* Sin borde */
    border-radius: 5px; /* Bordes redondeados */
    cursor: pointer; /* Cambiar cursor al pasar sobre el botón */
}

input[type=submit]:hover {
    background-color: #0056b3; /* Color más oscuro al pasar el mouse */
}

.error {
   color: red; /* Color rojo para mensajes de error */
   text-align: center;
}
p {
   text-align: center;
}
a {
   color: #007bff; /* Color azul para los enlaces */
}
</style>