<?php
// Verificar si se recibió el ID del contacto por GET
if (!isset($_GET['id'])) {
    die("ID de contacto no especificado.");
}

$id_contacto = $_GET['id'];

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

// Obtener los datos del contacto
$sql = "SELECT * FROM Contacto WHERE ID_Contacto = ?";
$params = array($id_contacto);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
}

$contacto = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Comprobar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $fecha_cumple = $_POST['fecha_cumple'];

    // Actualizar el contacto
    $sql_update = "UPDATE Contacto SET Nombre = ?, Direccion = ?, Telefono = ?, Correo = ?, Fecha_cumple = ? WHERE ID_Contacto = ?";
    $params_update = array($nombre, $direccion, $telefono, $correo, $fecha_cumple, $id_contacto);
    $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);

    if ($stmt_update === false) {
        die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
    } else {
        // Actualizar el evento de cumpleaños asociado
        $titulo = "Cumpleaños de $nombre";
        $descripcion = "Cumpleaños de $nombre";
        $id_evento = $contacto['ID_Evento'];

        $sql_update_evento = "UPDATE Evento SET Titulo = ?, Fecha = ?, Descripcion = ? WHERE ID_Evento = ?";
        $params_update_evento = array($titulo, $fecha_cumple, $descripcion, $id_evento);
        $stmt_update_evento = sqlsrv_query($conn, $sql_update_evento, $params_update_evento);

        if ($stmt_update_evento === false) {
            die("Error al actualizar el evento de cumpleaños: " . print_r(sqlsrv_errors(), true));
        } else {
            echo "Contacto y evento de cumpleaños actualizados correctamente.";
            header("Location: listar_contactos.php");
            exit;
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
    <title>Modificar Contacto</title>
</head>
<body>
    <h2>Modificar Contacto</h2>
    <form action="modificar_contacto.php?id=<?php echo $id_contacto; ?>" method="POST">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" value="<?php echo $contacto['Nombre']; ?>" required><br>
        <label for="apellido">Apellido:</label><br>
        <input type="text" id="apellido" name="apellido" value="<?php echo $contacto['Apellido']; ?>" required><br>
        <label for="direccion">Dirección:</label><br>
        <input type="text" id="direccion" name="direccion" value="<?php echo $contacto['Direccion']; ?>" required><br>
        <label for="telefono">Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono" value="<?php echo $contacto['Telefono']; ?>" required><br>
        <label for="correo">Correo:</label><br>
        <input type="email" id="correo" name="correo" value="<?php echo $contacto['Correo']; ?>" required><br>
        <label for="fecha_cumple">Fecha de cumpleaños:</label><br>
        <input type="date" id="fecha_cumple" name="fecha_cumple" value="<?php echo $contacto['Fecha_Cumple']; ?>" required><br><br>
        <button type="submit">Modificar Contacto</button>
    </form>
    <p><a href="listar_contactos.php">Volver a la lista de contactos</a></p>
</body>
</html>
