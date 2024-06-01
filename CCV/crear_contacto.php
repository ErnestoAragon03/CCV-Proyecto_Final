<?php
// Conexión a la base de datos
$serverName = "localhost";
$connectionOptions = array(
    "Database" => "CCVDB",
    "ReturnDatesAsStrings" => true
);
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Verificar la conexión
if (!$conn) {
    die("La conexión falló: " . print_r(sqlsrv_errors(), true));
}

// Consultar los tipos de eventos
$sql_tipos = "SELECT ID_Tipo, Nombre_Evento FROM Tipo_Evento";
$stmt_tipos = sqlsrv_query($conn, $sql_tipos);
$tipos_eventos = [];
while ($row = sqlsrv_fetch_array($stmt_tipos, SQLSRV_FETCH_ASSOC)) {
    $tipos_eventos[] = $row;
}

// Comprobar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha_cumple = $_POST['fecha_cumple'];
    $id_usuario = 1; // Suponiendo que el ID de usuario está hardcodeado, puedes cambiar esto según sea necesario

    // Obtener el último ID_Contacto y generar uno nuevo
    $sql_last_id_contacto = "SELECT MAX(ID_Contacto) AS MaxID FROM Contacto";
    $stmt_last_id_contacto = sqlsrv_query($conn, $sql_last_id_contacto);
    $row_last_id_contacto = sqlsrv_fetch_array($stmt_last_id_contacto, SQLSRV_FETCH_ASSOC);
    $next_id_contacto = $row_last_id_contacto['MaxID'] + 1;

    // Insertar el nuevo contacto
    $sql = "INSERT INTO Contacto (ID_Contacto, Nombre, Apellido, Fecha_Cumple, ID_Usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array($next_id_contacto, $nombre, $apellido, $fecha_cumple, $id_usuario);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
    } else {
        // Crear el evento de cumpleaños
        $titulo = "Cumpleaños de $nombre $apellido";
        $hora = "00:00:00";
        $descripcion = "Cumpleaños de $nombre $apellido";
        $id_tipo = 1; // Suponiendo que ID_Tipo 1 es para cumpleaños

        $sql_last_id_evento = "SELECT MAX(ID_Evento) AS MaxID FROM Evento";
        $stmt_last_id_evento = sqlsrv_query($conn, $sql_last_id_evento);
        $row_last_id_evento = sqlsrv_fetch_array($stmt_last_id_evento, SQLSRV_FETCH_ASSOC);
        $next_id_evento = $row_last_id_evento['MaxID'] + 1;

        $sql_evento = "INSERT INTO Evento (ID_Evento, Titulo, Fecha, Hora, Descripcion, ID_Usuario, ID_Tipo) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params_evento = array($next_id_evento, $titulo, $fecha_cumple, $hora, $descripcion, $id_usuario, $id_tipo);
        $stmt_evento = sqlsrv_query($conn, $sql_evento, $params_evento);

        if ($stmt_evento === false) {
            die("Error al crear el evento de cumpleaños: " . print_r(sqlsrv_errors(), true));
        } else {
            // Actualizar el contacto con el ID del evento de cumpleaños
            $sql_update_contacto = "UPDATE Contacto SET ID_Evento = ? WHERE ID_Contacto = ?";
            $params_update_contacto = array($next_id_evento, $next_id_contacto);
            $stmt_update_contacto = sqlsrv_query($conn, $sql_update_contacto, $params_update_contacto);

            if ($stmt_update_contacto === false) {
                die("Error al actualizar el contacto: " . print_r(sqlsrv_errors(), true));
            } else {
                echo "Contacto y evento de cumpleaños creados correctamente.";
                header("Location: listar_contactos.php");
                exit;
            }
        }
    }

    // Cerrar la conexión
    sqlsrv_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Contacto</title>
</head>
<body>
    <h2>Crear Contacto</h2>
    <form action="crear_contacto.php" method="POST">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br>s
        <label for="apellido">Apellido:</label><br>
        <input type="text" id="apellido" name="apellido" required><br>
        <label for="fecha_cumple">Fecha de Cumpleaños:</label><br>
        <input type="date" id="fecha_cumple" name="fecha_cumple" required><br><br>
        <button type="submit">Crear Contacto</button>
    </form>
    <p><a href="listar_contactos.php">Volver a la lista de contactos</a></p>
</body>
</html>
