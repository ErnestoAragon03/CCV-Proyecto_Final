<!DOCTYPE html>
<html>
<head>
    <title>Elegir Opción</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        p {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 10px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
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

    <form action="listar_eventos.php" method="get">
        <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>">
        <button type="submit">Listado Evento</button>
    </form>

    <form action="crear_contacto.php" method="get">
        <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>">
        <button type="submit">Crear Contacto</button>
    </form>

    <form action="listar_contactos.php" method="get">
        <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>">
        <button type="submit">Listar Contactos</button>
    </form>

    <form action="administrar_tipoEvento.php" method="get">
        <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>">
        <button type="submit">Tipos de Evento</button>
    </form>

    <form action="calendario.php" method="get">
        <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>">
        <button type="submit">Ver Calendario</button>
    </form>

   
</body>
</html>
