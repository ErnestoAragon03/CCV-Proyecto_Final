<!DOCTYPE html>
<html>
<head>
    <title>Elegir Opción</title>
</head>
<body>
    <h1>Elegir Opción</h1>
    <?php
    // Obtener la fecha desde el parámetro de la URL
    if (isset($_GET['fecha'])) {
        $fecha = $_GET['fecha'];
    } else {
        die("No se ha proporcionado una fecha.");
    }
    ?>

    <p>Fecha seleccionada: <?php echo htmlspecialchars($fecha); ?></p>

    <form action="crear_evento.php" method="get">
        <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>">
        <button type="submit">Crear Evento</button>
    </form>

    <form action="crear_contacto.php" method="get">
        <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>">
        <button type="submit">Crear Contacto</button>
    </form>

    <form action="listar_contactos.php" method="get">
        <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>">
        <button type="submit">Listar Contactos</button>
    </form>
</body>
</html>
