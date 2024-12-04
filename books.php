<?php
session_start();
include 'db.php'; // Asegúrate de incluir db.php para acceder a $pdo

// Redirigir a la página de login si el usuario no está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Manejar préstamo y devolución de libros
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $libro_id = filter_input(INPUT_POST, 'libro_id', FILTER_VALIDATE_INT);
    $usuario_id = $_SESSION['user_id'];

    if ($libro_id === false) {
        die("ID del libro no válido.");
    }

    if (isset($_POST['prestamo'])) {
        // Prestar libro solo si hay ejemplares disponibles
        $stmt = $pdo->prepare("SELECT estado, ejemplares FROM libros WHERE id = ?");
        $stmt->execute([$libro_id]);
        $libro = $stmt->fetch();

        if ($libro && $libro['estado'] == 'disponible' && $libro['ejemplares'] > 0) {
            // Actualizar ejemplares y registrar préstamo
            $stmt = $pdo->prepare("UPDATE libros SET ejemplares = ejemplares - 1 WHERE id = ?");
            $stmt->execute([$libro_id]);

            // Registrar préstamo en la tabla prestamos
            $stmt = $pdo->prepare("INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo) VALUES (?, ?, NOW())");
            $stmt->execute([$usuario_id, $libro_id]);
            echo "Libro prestado con éxito.";
        } else {
            echo "El libro no está disponible o no hay ejemplares suficientes.";
        }
    }

    if (isset($_POST['devolver'])) {
        // Cambiar estado del libro a disponible (devuelto) y aumentar ejemplares
        $stmt = $pdo->prepare("UPDATE libros SET ejemplares = ejemplares + 1 WHERE id = ?");
        $stmt->execute([$libro_id]);

        // Comprobar si ahora hay al menos un ejemplar disponible para actualizar el estado
        $stmt = $pdo->prepare("SELECT ejemplares FROM libros WHERE id = ?");
        $stmt->execute([$libro_id]);
        $libro = $stmt->fetch();

        if ($libro && $libro['ejemplares'] > 0) {
            // Si hay ejemplares disponibles, actualizar el estado a 'disponible'
            $stmt = $pdo->prepare("UPDATE libros SET estado = 'disponible' WHERE id = ?");
            $stmt->execute([$libro_id]);
        }

        echo "Estatus del libro actualizado a disponible.";
    }
}

// Consultar solo los libros que están disponibles en existencia
$stmt = $pdo->query("SELECT * FROM libros WHERE estado = 'disponible' AND ejemplares > 0");
$librosDisponibles = $stmt->fetchAll();

// Obtener información del usuario actual
$stmtUser = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$usuarioActual = $stmtUser->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="style.css">
   <title>Libros Disponibles</title>
</head>
<body>

<?php include 'header.php'; ?> <!-- Incluir el menú y la cabecera -->

<div class="content">
<h2>Libros Disponibles</h2>

<!-- Mostrar información del usuario -->
<div class="usuario-info">
   <h3>Bienvenido, <?php echo htmlspecialchars($usuarioActual['nombre']); ?></h3>
</div>

<table>
   <tr>
       <th>Título</th>
       <th>Autor</th>
       <th>Edición</th>
       <th>Editorial</th>
       <th>Lugar de Edición</th>
       <th>Año</th>
       <th>Procedencia</th>
       <th>Ejemplares</th>
       <th>Estado</th>
       <th>Acción</th>
   </tr>

   <?php foreach ($librosDisponibles as $libro): ?>
       <tr>
           <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
           <td><?php echo htmlspecialchars($libro['autor']); ?></td>
           <td><?php echo htmlspecialchars($libro['edicion']); ?></td>
           <td><?php echo htmlspecialchars($libro['editorial']); ?></td>
           <td><?php echo htmlspecialchars($libro['lugar_edicion']); ?></td>
           <td><?php echo htmlspecialchars($libro['anio']); ?></td>
           <td><?php echo htmlspecialchars($libro['procedencia']); ?></td>
           <td><?php echo htmlspecialchars($libro['ejemplares']); ?></td>
           <td id="estado-<?php echo htmlspecialchars($libro['id']); ?>"><?php echo htmlspecialchars($libro['estado']); ?></td>

           <!-- Columna de acción -->
           <td>
               <!-- Botón para prestar el libro -->
               <form method="POST" style="display:inline;">
                   <input type="hidden" name="libro_id" value="<?php echo htmlspecialchars($libro['id']); ?>">
                   <button type="submit" name="prestamo">Prestar</button>
               </form>

           </td>

       </tr>

   <?php endforeach; ?>
   
</table>

<script src='script.js'></script>

<?php include 'footer.php'; ?> <!-- Incluir el footer -->

</body>
</html>