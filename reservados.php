<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Libros Reservados</title>
</head>
<body>

<?php include 'header.php'; ?> <!-- Incluir el menú y la cabecera -->

<div class="content">
    <h2>Libros Reservados</h2>
    <?php if (empty($reservados)): ?>
        <p>No tienes libros reservados.</p> <!-- Mensaje si no hay reservas -->
    <?php else: ?>
        <table>
            <tr>
                <th>Título</th>
                <th>Autor</th>
            </tr>
            <?php foreach ($reservados as $libro): ?>
                <tr>
                    <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($libro['autor']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?> <!-- Incluir el footer -->

<script src="script.js"></script> <!-- Incluir el archivo JavaScript -->

</body>
</html>