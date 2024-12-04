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
        // Cambiar estado del libro a disponible (devuelto)
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

        // Eliminar el préstamo correspondiente
        $stmt = $pdo->prepare("DELETE FROM prestamos WHERE usuario_id = ? AND libro_id = ?");
        $stmt->execute([$usuario_id, $libro_id]);

        echo "Estatus del libro actualizado a disponible y préstamo eliminado.";
    }
}

// Consultar solo los libros que están actualmente prestados por el usuario
$usuario_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT l.*, p.fecha_prestamo 
    FROM libros l 
    JOIN prestamos p ON l.id = p.libro_id 
    WHERE p.usuario_id = ?
");
$stmt->execute([$usuario_id]);
$librosPrestados = $stmt->fetchAll();

// Obtener información del usuario actual
$stmtUser = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmtUser->execute([$usuario_id]);
$usuarioActual = $stmtUser->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="style.css">
   <title>Historial de Préstamos</title>
</head>
<body>

<?php include 'header.php'; ?> <!-- Incluir el menú y la cabecera -->

<div class="content">
<h2>Historial de Préstamos</h2>

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
       <th>Ejemplares en Préstamo</th> <!-- Cambiado a Ejemplares en Préstamo -->
       <th>Fecha de Préstamo</th>
       <th>Acción</th>
   </tr>

   <?php if (empty($librosPrestados)): ?>
       <tr>
           <td colspan="10">No tienes libros en préstamo.</td>
       </tr>
   <?php else: ?>
       <?php foreach ($librosPrestados as $libro): ?>
           <tr>
               <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
               <td><?php echo htmlspecialchars($libro['autor']); ?></td>
               <td><?php echo htmlspecialchars($libro['edicion']); ?></td>
               <td><?php echo htmlspecialchars($libro['editorial']); ?></td>
               <td><?php echo htmlspecialchars($libro['lugar_edicion']); ?></td>
               <td><?php echo htmlspecialchars($libro['anio']); ?></td>
               <td><?php echo htmlspecialchars($libro['procedencia']); ?></td>

               <!-- Mostrar cuántos ejemplares tiene el usuario en préstamo -->
               <?php 
                   // Contar cuántos ejemplares tiene prestados este libro
                   $stmtCount = $pdo->prepare("SELECT COUNT(*) as total FROM prestamos WHERE usuario_id = ? AND libro_id = ?");
                   $stmtCount->execute([$usuario_id, $libro['id']]);
                   $totalPrestadosPorUsuario = $stmtCount->fetchColumn();
               ?>
               <td><?php echo htmlspecialchars($totalPrestadosPorUsuario); ?></td>

               <td><?php echo htmlspecialchars($libro['fecha_prestamo']); ?></td>

               <!-- Columna de acción -->
               <td>
                   <!-- Botón para devolver el libro -->
                   <form method="POST" style="display:inline;">
                       <input type="hidden" name="libro_id" value="<?php echo htmlspecialchars($libro['id']); ?>">
                       <button type="submit" name="devolver">Devolver</button>
                   </form>
               </td>

           </tr>

       <?php endforeach; ?>
   <?php endif; ?>
   
</table>

<script src='script.js'></script>

<?php include 'footer.php'; ?> <!-- Incluir el footer -->

</body>
</html>