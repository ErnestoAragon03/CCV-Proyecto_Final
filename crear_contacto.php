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
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['email'];
    $fecha_cumple = $_POST['fecha_cumple'];
    $id_usuario = 1; // Suponiendo que el ID de usuario está hardcodeado, puedes cambiar esto según sea necesario

    // Verificar longitud del teléfono y rellenar con ceros si es necesario
    if (strlen($telefono) > 8) {
        die("El número de teléfono no puede tener más de 8 caracteres.");
    }
    $telefono = str_pad($telefono, 8, "0", STR_PAD_LEFT);

    // Obtener el último ID_Contacto y generar uno nuevo
    $sql_last_id_contacto = "SELECT MAX(ID_Contacto) AS MaxID FROM Contacto";
    $stmt_last_id_contacto = sqlsrv_query($conn, $sql_last_id_contacto);
    $row_last_id_contacto = sqlsrv_fetch_array($stmt_last_id_contacto, SQLSRV_FETCH_ASSOC);
    $next_id_contacto = $row_last_id_contacto['MaxID'] + 1;

    // Insertar el nuevo contacto
    $sql = "INSERT INTO Contacto (ID_Contacto, Nombre, Direccion, Telefono, Correo, Fecha_Na, ID_Usuario) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $params = array($next_id_contacto, $nombre, $direccion, $telefono, $correo, $fecha_cumple, $id_usuario);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
    } else {
        // Crear el evento de cumpleaños
        $titulo = "Cumpleaños de $nombre";
        $titulo = substr($titulo, 0, 20); // Truncar el título a 20 caracteres
        $hora = "00:00:00";
        $descripcion = "Cumpleaños de $nombre";
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
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <h2>Crear Contacto</h2>
        <form action="crear_contacto.php" method="POST">
            <div class="field">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="field">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required>
            </div>
            <div class="field">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" required>
            </div>
            <div class="field">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="field">
                <label for="fecha_cumple">Fecha de Cumpleaños:</label>
                <input type="date" id="fecha_cumple" name="fecha_cumple" required>
            </div>
            <button type="submit">Crear Contacto</button>
            
        </form>
        <p><a href="listar_contactos.php">Volver a la lista de contactos</a></p>
        <p><a href="calendario.php">Calendario</a></p>
    </div>
</body>
</html>
